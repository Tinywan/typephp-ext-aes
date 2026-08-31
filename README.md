# typephp-ext-aes

AES PHP extension built with TypePHP 0.6.5 for Windows.

## Prerequisites

- The complete TypePHP Windows package at `D:\workspace\tpc_v0.6.5_windows_x86_64`
- No system PHP or Composer installation is required: use the copies bundled in that package.

## Build

Run the following from this repository in PowerShell:

```powershell
$tpcHome = 'D:\workspace\tpc_v0.6.5_windows_x86_64'
$env:PHP_HOME = $tpcHome
$env:PHPX_HOME = Join-Path $tpcHome 'phpx'
$env:Path = "$tpcHome;$env:Path"

& "$tpcHome\composer.bat" install --no-interaction --prefer-dist
& "$tpcHome\tpc.exe" .\project.yml
```

The generated extension is written below `build\` (normally
`build\typephp_ext_aes.dll` on Windows or `build/typephp_ext_aes.so` on Linux). The generated `vendor\` and `build\`
directories are intentionally ignored by Git.

`main.php` is a standalone TypePHP binary-mode example and is deliberately not
listed in `project.yml`; the extension build contains only `security\EncryptionUtil`.

## Troubleshooting

If `tpc.exe` reports that `vendor/autoload.php` cannot be opened, run the
Composer command above from the repository root. The Windows `tpc.exe` package
loads compiler PHP dependencies through the project's `vendor/autoload.php`,
so `swoole/typephp` is pinned as a development build dependency and must remain
installed while compiling.
