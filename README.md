# Telegram Git Notifier Core Package

This package provides the ability to integrate the Telegram messaging service with GitHub and GitLab.
With this package, you can create a Telegram bot to receive notifications from GitHub or GitLab events
and manage customization through messages and buttons on Telegram.

<p align="center">
  <img alt="Telegram git notifier core" src="https://github.com/cslant/telegram-git-notifier/assets/35853002/d731d731-3c32-40a7-80d2-b079d7f1de6c" />
</p>

<p align="center">
<a href="#"><img src="https://img.shields.io/github/license/cslant/telegram-git-notifier.svg?style=flat-square" alt="License"></a>
<a href="https://packagist.org/cslant/telegram-git-notifier"><img src="https://img.shields.io/packagist/v/cslant/telegram-git-notifier.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/cslant/telegram-git-notifier"><img src="https://img.shields.io/packagist/dt/cslant/telegram-git-notifier.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://github.com/cslant/telegram-git-notifier/actions/workflows/setup_test.yml"><img src="https://img.shields.io/github/actions/workflow/status/cslant/telegram-git-notifier/setup_test.yml?label=tests&branch=main" alt="Test Status"></a>
<a href="https://github.com/cslant/telegram-git-notifier/actions/workflows/php-cs-fixer.yml"><img src="https://img.shields.io/github/actions/workflow/status/cslant/telegram-git-notifier/php-cs-fixer.yml?label=code%20style&branch=main" alt="Code Style Status"></a>
<a href="https://scrutinizer-ci.com/g/cslant/telegram-git-notifier"><img src="https://img.shields.io/scrutinizer/g/cslant/telegram-git-notifier.svg?style=flat-square" alt="Quality Score"></a>
<a href="https://codeclimate.com/github/cslant/telegram-git-notifier/maintainability"><img src="https://api.codeclimate.com/v1/badges/b6f16164d55809d0918e/maintainability" alt="Maintainability"></a>
</p>

## 📋 Requirements

- PHP ^8.4|^8.5
- [Composer](https://getcomposer.org/)
- [Telegram Bot](https://core.telegram.org/api)

## 🔧 Installation

You can install this package via Composer:

```bash
composer require cslant/telegram-git-notifier
```

## ✨ Features

- **Platform Enum**: Type-safe GitHub/GitLab platform handling
- **ChatTarget DTO**: Structured chat ID parsing with thread support
- **In-Memory Caching**: Config files cached in memory with dirty flag for efficient I/O
- **PSR-3 Logger**: Standardized logging interface support
- **API Retry Logic**: Exponential backoff for Telegram API rate limits (HTTP 429)
- **Safe Template Rendering**: Closure-isolated scope for view templates
- **Strict Types**: Full type safety with `readonly` properties and explicit nullable params

## 🧪 Testing

```bash
composer test
```

## 📖 Official Documentation

Please see the [Telegram Git Notifier Documentation](https://docs.cslant.com/telegram-git-notifier) for more information.

## 📦 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
