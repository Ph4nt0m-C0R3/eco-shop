const {spawnSync} = require('node:child_process');

function git(args) {
  const result = spawnSync('git', args, {encoding: 'utf8'});
  if (result.error || result.status !== 0) throw new Error(result.error?.message || result.stderr.trim());
  return result.stdout;
}

try {
  const files = git(['ls-files', '--cached', '-z']).split('\0').filter(Boolean);
  const forbidden = files.filter(file =>
    /^(audit|dist|node_modules|vendor|\.tools|\.aws|\.agents|\.codex)\//.test(file)
    || /^public\/(storage|hot)(\/|$)/.test(file)
    || /(^|\/)\.env($|\.)/.test(file) && file !== '.env.example'
    || /\.(sqlite3?|sql|pem|key|p12)$/i.test(file)
    || /^storage\/app\//.test(file) && !file.endsWith('/.gitignore') && !/^storage\/app\/public\/default\//.test(file)
    || /^storage\/(logs|framework)\//.test(file) && !file.endsWith('/.gitignore')
    || /^bootstrap\/cache\//.test(file) && !file.endsWith('/.gitignore')
  );
  for (const required of ['composer.lock', 'package-lock.json', '.github/workflows/ci.yml']) {
    if (!files.includes(required)) forbidden.push(`missing required tracked file: ${required}`);
  }
  const pattern = 'sk_live_[A-Za-z0-9]{16,}|rk_live_[A-Za-z0-9]{16,}|AKIA[0-9A-Z]{16}|gh[pousr]_[A-Za-z0-9]{30,}|-----BEGIN (RSA |EC |OPENSSH )?PRIVATE KEY-----';
  const secrets = spawnSync('git', ['grep', '--cached', '-I', '-l', '-E', '-e', pattern], {encoding: 'utf8'});
  if (secrets.error || ![0, 1].includes(secrets.status)) throw new Error(secrets.error?.message || secrets.stderr.trim());
  const secretFiles = secrets.stdout.trim().split('\n').filter(Boolean);
  if (forbidden.length || secretFiles.length) {
    console.error('Repository check failed. Remove these files/credentials from the index:');
    for (const file of [...forbidden, ...secretFiles]) console.error(`- ${file}`);
    process.exitCode = 1;
  } else {
    console.log(`Repository check passed: ${files.length} tracked files; no prohibited data paths or detected live credentials.`);
  }
} catch (error) {
  console.error(error.message);
  process.exitCode = 1;
}
