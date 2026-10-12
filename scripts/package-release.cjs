const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');
const {spawnSync} = require('node:child_process');
const {forbidden} = require('./verify-release.cjs');
const root = process.cwd();

function run(command, args) {
  const result = spawnSync(command, args, {encoding: 'utf8', maxBuffer: 32 * 1024 * 1024});
  if (result.error || result.status !== 0) throw new Error(result.error?.message || result.stderr.trim() || result.stdout.trim());
  return result.stdout.trim();
}
function hash(file) { return crypto.createHash('sha256').update(fs.readFileSync(file)).digest('hex'); }
function files(directory) {
  return fs.readdirSync(directory, {withFileTypes: true}).flatMap(entry => {
    const file = path.join(directory, entry.name);
    if (entry.isSymbolicLink()) throw new Error(`Symlink cannot be packaged: ${file}`);
    return entry.isDirectory() ? files(file) : [file];
  });
}

try {
  if (run('git', ['status', '--porcelain']).length) throw new Error('Commit the source changes before packaging a release.');
  const commit = run('git', ['rev-parse', 'HEAD']);
  if (!fs.existsSync('public/build/manifest.json')) throw new Error('Run npm ci and npm run build first.');
  if (!fs.existsSync('vendor/composer/installed.json')) throw new Error('Install the locked production Composer dependencies first.');
  const installed = JSON.parse(fs.readFileSync('vendor/composer/installed.json', 'utf8'));
  if (installed.dev !== false) throw new Error('Release dependencies include development packages. Use composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader in an isolated checkout.');
  const base = path.resolve(root, 'dist');
  const stage = path.join(base, `stage-${commit.slice(0, 12)}`);
  const archive = path.join(base, `ecoshop-v2.0-${commit.slice(0, 12)}.tar.gz`);
  if (fs.existsSync(stage) || fs.existsSync(archive)) throw new Error(`Release output already exists: ${stage}. Use a fresh checkout to rebuild this commit.`);
  fs.mkdirSync(stage, {recursive: true});
  const source = path.join(base, `source-${commit.slice(0, 12)}.tar`);
  run('git', ['archive', '--format=tar', `--output=${source}`, commit]);
  run('tar', ['-xf', source, '-C', stage]);
  fs.cpSync('public/build', path.join(stage, 'public/build'), {recursive: true, dereference: false});
  fs.cpSync('vendor', path.join(stage, 'vendor'), {recursive: true, dereference: false});
  const manifest = {
    brand: 'EcoShop v2.0', sourceCommit: commit,
    node: process.version, platform: process.platform,
    composerLockSha256: hash('composer.lock'), npmLockSha256: hash('package-lock.json'),
    files: {},
  };
  for (const file of files(stage)) {
    const relative = path.relative(stage, file).replaceAll('\\', '/');
    if (forbidden(relative)) throw new Error(`Prohibited deployment file: ${relative}`);
    manifest.files[relative] = hash(file);
  }
  fs.writeFileSync(path.join(stage, 'release-manifest.json'), JSON.stringify(manifest, null, 2) + '\n');
  run('tar', ['-czf', archive, '-C', stage, '.']);
  console.log(run(process.execPath, [path.join(root, 'scripts/verify-release.cjs'), archive]));
  const checksum = hash(archive);
  fs.writeFileSync(`${archive}.sha256`, `${checksum}  ${path.basename(archive)}\n`);
  console.log(`Release: ${archive}\nSHA256: ${checksum}\nSource: ${commit}`);
} catch (error) {
  console.error(error.message);
  process.exitCode = 1;
}
