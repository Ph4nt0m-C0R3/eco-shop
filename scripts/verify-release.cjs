const fs = require('node:fs');
const path = require('node:path');
const {spawnSync} = require('node:child_process');
const archive = process.argv[2];

function forbidden(file) {
  file = file.replace(/^\.\//, '').replace(/\/$/, '');
  if (!file) return false;
  return /^(audit|dist|\.git|\.github|\.tools|\.aws|\.codex|\.agents|node_modules|tests|docs)(\/|$)/.test(file)
    || /(^|\/)\.env($|\.)/.test(file)
    || /\.(sqlite3?|sql|pem|key|p12)$/i.test(file)
    || /^database\/(seeders|factories)(\/|$)/.test(file)
    || /^public\/(storage|hot)(\/|$)/.test(file)
    || /^phpunit.*\.xml$/.test(file) || file === 'future-improvements.txt'
    || /^bootstrap\/cache\/.+/.test(file) && !file.endsWith('/.gitignore')
    || /^storage\/(logs|framework)\/.+/.test(file) && !file.endsWith('/.gitignore')
      && !['storage/framework/cache', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/framework/testing'].includes(file)
    || /^storage\/app\/(private|public)\/.+/.test(file) && !file.endsWith('/.gitignore') && !/^storage\/app\/public\/default(\/|$)/.test(file)
    || /^storage\/app\/public\/default\/.*_qr\.png$/i.test(file);
}

module.exports = {forbidden};
if (require.main === module) {
  try {
    if (!archive || !fs.existsSync(archive)) throw new Error('Usage: node scripts/verify-release.cjs <release.tar.gz>');
    const result = spawnSync('tar', ['-tzf', path.resolve(archive)], {encoding: 'utf8', maxBuffer: 32 * 1024 * 1024});
    if (result.error || result.status !== 0) throw new Error(result.error?.message || result.stderr.trim());
    const files = result.stdout.trim().split(/\r?\n/);
    const bad = files.filter(forbidden);
    for (const required of ['composer.lock', 'package-lock.json', 'release-manifest.json', 'vendor/autoload.php', 'public/build/manifest.json', 'storage/app/public/default/logoText.png']) {
      if (!files.some(file => file.replace(/^\.\//, '') === required)) bad.push(`missing ${required}`);
    }
    if (bad.length) throw new Error('Unsafe/incomplete release entries:\n' + bad.join('\n'));
    console.log(`Release check passed: ${files.length} entries; production dependencies/assets present; local data, credentials and demo seeders excluded.`);
  } catch (error) {
    console.error(error.message);
    process.exitCode = 1;
  }
}
