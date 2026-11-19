import { cpSync, rmSync, existsSync } from 'node:fs';

const skip = new Set(['index.php', '.htaccess', 'hot', 'fonts-manifest.dev.json']);

if (existsSync('dist')) {
    rmSync('dist', { recursive: true, force: true });
}

cpSync('public', 'dist', {
    recursive: true,
    filter: (src) => {
        const name = src.split(/[\\/]/).pop();
        return !skip.has(name);
    },
});

console.log('dist folder created from public');
