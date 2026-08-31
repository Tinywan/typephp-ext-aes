# TypePHP Ext AES

基于 [TypePHP](https://github.com/swoole/typephp) AOT 原生编译器与 [PHPX](https://github.com/swoole/phpx) 开发的高性能 PHP AES 加解密原生扩展模块。

支持 **Linux x64 (`.so`)** 与 **Windows x64 (`.dll`)** 双平台。

---

## 🌟 特性

- **算法支持**：AES-128-ECB 加密算法，支持 PKCS7Padding 填充方式。
- **高性能原生执行**：PHP 代码直接编译为 C++17 原生动态库，执行速度快，内存开销小。
- **开箱即用**：GitHub Actions 自动构建跨平台二进制发布包，无需本地编译环境即可直接集成。
- **标准 PHP 接口**：提供面向对象的静态方法，开箱即用。

---

## 📋 环境要求

- **PHP 版本**：`PHP >= 8.5`
- **依赖扩展**：`openssl` 扩展（必须启用）
- **架构支持**：`x86_64 / x64`

---

## 🚀 快速安装

从 [GitHub Releases](https://github.com/Tinywan/typephp-ext-aes/releases) 下载对应系统的最新预编译压缩包：

### 1. Windows 环境 (`.dll`)

1. 下载并解压 `typephp-ext-aes-php8.5-windows-x64.zip`；
2. 将解压出来的 `typephp_ext_aes.dll` 和 `phpx.dll` 复制到 PHP 的 `ext/` 扩展目录（或放置在系统 PATH 中）；
3. 在 `php.ini` 中启用扩展：
   ```ini
   extension=openssl
   extension=typephp_ext_aes
   ```
4. 验证安装：
   ```powershell
   php -m | findstr typephp_ext_aes
   ```

### 2. Linux 环境 (`.so`)

1. 下载并解压 `typephp-ext-aes-php8.5-linux-x64.tar.gz`：
   ```bash
   tar -zxvf typephp-ext-aes-php8.5-linux-x64.tar.gz
   ```
2. 将 `typephp_ext_aes.so` 与 `libphpx.so` 复制到 PHP 扩展目录：
   ```bash
   sudo cp typephp_ext_aes.so libphpx.so $(php-config --extension-dir)/
   ```
3. 在 `php.ini` 中追加扩展配置：
   ```ini
   extension=openssl
   extension=typephp_ext_aes.so
   ```
4. 验证安装：
   ```bash
   php -m | grep typephp_ext_aes
   ```

---

## 💡 使用示例

```php
<?php
declare(strict_types=1);

use security\src\EncryptionUtil;

// 1. 加密
$rawText = "Hello TypePHP AES Encryption!";
$encrypted = EncryptionUtil::encrypt($rawText);

echo "【原文】: " . $rawText . PHP_EOL;
echo "【密文】: " . $encrypted . PHP_EOL;

// 2. 解密
$decrypted = EncryptionUtil::decrypt($encrypted);
echo "【解密】: " . $decrypted . PHP_EOL;

if ($rawText === $decrypted) {
    echo "🎉 加解密验证成功！" . PHP_EOL;
}
```

### API 参考

```php
namespace security\src;

class EncryptionUtil
{
    /**
     * AES-128-ECB 加密
     *
     * @param string $encryptText 待加密字符串
     * @return string Base64 编码后的密文字符串
     */
    public static function encrypt(string $encryptText): string;

    /**
     * AES-128-ECB 解密
     *
     * @param string $encryptedText Base64 编码的密文字符串
     * @return false|string 解密成功返回明文字符串，失败返回 false
     */
    public static function decrypt(string $encryptedText): false|string;
}
```

---

## 🛠️ 本地从源码构建

### Windows 环境

1. 下载官方 [TypePHP Windows SDK](https://github.com/swoole/typephp/releases)（如 `tpc_v0.6.6_windows_x64.zip`）并解压；
2. 打开具备 **Visual Studio 2022 (MSVC 14.44)** 的 Developer PowerShell：
   ```powershell
   $tpcHome = 'D:\workspace\tpc_v0.6.6_windows_x64'
   $env:PHP_HOME = $tpcHome
   $env:PHPX_HOME = Join-Path $tpcHome 'phpx'
   $env:Path = "$tpcHome;$env:Path"

   # 安装依赖并执行编译
   composer update swoole/typephp
   & "$tpcHome\tpc.exe" .\project.yml
   ```
3. 编译产物位于 `build\typephp_ext_aes.dll`。

### Linux 环境

1. 安装基础编译依赖：
   ```bash
   sudo apt-get update
   sudo apt-get install --yes build-essential cmake pkg-config libgmp-dev libmpfr-dev patchelf
   ```
2. 安装 Composer 依赖并编译 PHPX：
   ```bash
   composer update swoole/typephp
   mkdir -p vendor/swoole/typephp/vendor
   ln -sf "$PWD/vendor/autoload.php" vendor/swoole/typephp/vendor/autoload.php

   # 编译 PHPX
   cmake -S vendor/swoole/phpx -B .typephp/phpx-build \
     -DCMAKE_BUILD_TYPE=Release \
     -Dphp_dir="$(php-config --prefix)" \
     -DCMAKE_LIBRARY_OUTPUT_DIRECTORY="$PWD/vendor/swoole/phpx/lib"
   cmake --build .typephp/phpx-build --target phpx --parallel 2
   ```
3. 执行 AOT 扩展编译：
   ```bash
   export PHP_HOME="$(php-config --prefix)"
   export PHPX_HOME="$PWD/vendor/swoole/phpx"
   vendor/bin/tpc.php project.yml
   ```
4. 编译产物位于 `build/typephp_ext_aes.so`。

---

## ⚙️ CI/CD 自动化流水线

本项目配置了完整的 GitHub Actions 流水线（[`.github/workflows/build.yml`](.github/workflows/build.yml)）：
- **多平台矩阵构建**：每次提交自动触发 Windows x64 与 Linux x64 编译及测试；
- **环境对齐**：Windows 严格锁定 MSVC 14.44 工具链与 Thread-Safe PHP ABI；
- **自动化 Release**：推送版本 Tag（如 `v*`）时，自动打包制品并创建 GitHub Release。

## ❓ 常见问题与排错指南 (FAQ)

### 1. Windows 下报 `Unable to load dynamic library ... (找不到指定的模块)`
- **原因 1：扩展名后缀写错为 `.so`**  
  在 Windows 的 `php.ini` 中，不能包含 Linux 的 `.so` 后缀。  
  ❌ 错误写法：`extension=typephp_ext_aes.so`  
  ✅ 正确写法：`extension=typephp_ext_aes` 或 `extension=typephp_ext_aes.dll`
- **原因 2：`extension_dir` 扩展目录未正确配置**  
  检查 `php.ini` 中的 `extension_dir` 是否指向当前 `ext` 目录（例如 `extension_dir = "ext"`），并确认 `typephp_ext_aes.dll` 以及 `phpx.dll` 均存放在该目录下。

### 2. 报 `Cannot load module "typephp_typephp_ext_aes" because required module "openssl" is not loaded`
- **原因**：本扩展底层调用了 PHP 的 `openssl` 扩展加解密 API。
- **解决方法**：在 `php.ini` 中必须同时开启 `extension=openssl`。如果使用命令行 `-d` 参数，需同时指定 `-d extension=openssl`。

### 3. Windows 终端输出中文乱码（如显示成 `鎵句笉鍒版寚瀹氱殑妯″潡`）
- **原因**：Windows 控制台默认代码页为 GBK (936)，而 PHP 8.4+ 默认以 UTF-8 输出。
- **解决方法**：在 PowerShell / CMD 中先执行以下命令切换为 UTF-8 编码：
  ```powershell
  chcp 65001
  [Console]::OutputEncoding = [System.Text.Encoding]::UTF8
  ```

### 4. Linux 下报 `libphpx.so: cannot open shared object file: No such file or directory`
- **原因**：动态链接器未找到 `libphpx.so` 共享库。
- **解决方法**：Release 发布包中已内置 `libphpx.so` 并配置了 `$ORIGIN` 搜索路径，请确保将 `libphpx.so` 与 `typephp_ext_aes.so` 放置在同一个目录下，或者配置 `LD_LIBRARY_PATH` 环境变量。

### 5. Windows 下报 `Can't load module as it's linked with 14.xx, but the core is linked with 14.yy`
- **原因**：扩展编译时使用的 MSVC 编译器版本与 PHP 核心（`php.exe`）编译版本不匹配（Zend 构建校验机制）。
- **解决方法**：请使用项目 Release 中发布的预编译包，或在本地编译时使用与 PHP 官方一致的 **Visual Studio 2022 (MSVC 14.44)** 工具链。

---

## 📄 License

MIT License.
