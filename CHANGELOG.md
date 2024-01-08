# Changelog

Here you can see the full list of changes between each Telegram Git Notifier release.

## v1.3.5 - 2023-12-03

### ✨ New Features:

- Allows adding ignore message string to view files to prevent sending notifications. (https://github.com/cslant/telegram-git-notifier/pull/35)

### 📝 What's Changed

- Optimize: Remove redundant param in setting event
- Change the return type for view function of core

### ✍ New Contributors

- @pxthinh made their first contribution in https://github.com/cslant/telegram-git-notifier/pull/35

## v1.3.4 - 2023-11-11

### 🔧 Improvements and Fixes

- Fixed the error with the function view if need to use namespace in the Laravel package #31

## v1.3.3 - 2023-11-08

### 📝 What's Changed

- Enhanced Exception Handling: We've expanded our exception handling to give you clearer insights into what's going wrong and why. (#29, #30)
- Config File Integrity Checks: To ensure the integrity and reliability of your notification system. (#29)

### 🔧 Improvements and Fixes

- Fixed some functions that conflict with Laravel framework.([b41efea](https://github.com/cslant/telegram-git-notifier/commit/b41efea3320edd1f3bdec75247ea0e67c4987916))

## v1.3.2 - 2023-11-04

### 🔧 Improvements and Fixes

- Fixed helper function conflict with Laravel by ensuring config and view are not redefined if they already exist. #28

## v1.3.1 - 2023-11-03

### 🔧 Improvements and Fixes

In this release, we've made some important fixes that streamline the setup process and ensure a smoother experience for all users.

- Automated Config Setup: Integration configuration files without navigating through complex installation steps. [c4b4187](https://github.com/cslant/telegram-git-notifier/commit/c4b41872e9b500c3a5cf1b51231b730ef1a49650)

## v1.3.0 - 2023-11-03

### ✨  New Release: Enhanced Features and Optimizations!

We are thrilled to announce the latest release with several new features and improvements:

- Bash **install.sh** Integration configuration files without navigating through complex installation steps. [d2982b1](https://github.com/cslant/telegram-git-notifier/commit/d2982b1cc4f3ae1a202a9cfab8dbdf443483bba9)
- Codebase refactoring for cleaner, more intuitive structures. #21, #23
- Integration of comprehensive test cases to ensure product reliability. #26
- Incorporated extensive testing for multiple PHP versions, ensuring increased robustness and optimal compatibility across different PHP editions. #24
- **chatIDs** Param for **Notifier**: Pass the chatIDs parameter when using the Notifier, allowing for more flexible and personalized notifications. #26
- Update namespaces recent branding changes #27, [UPGRADING.md](https://github.com/cslant/telegram-git-notifier/blob/v1.3.0/UPGRADING.md)

## v1.2.0 - 2023-10-20

- Add support for sending notifications in topics (threads in supergroups)
- Enhanced capability to send notifications across multiple topics simultaneously
- Add support for Telegram bot commands
- Refactor source code
- Update support for custom commands and buttons
- Enhanced support for custom callbacks

## v1.1.0 - 2023-10-14

- Add support for GitLab and GitHub events
- Add support for custom commands and buttons
- Add support for custom callbacks
- Better support sends notifications to multiple chats
- Validate the configuration platform events
- Refactor services
- Conditions to check if the configuration is valid
- And a lot more ...

## v1.0.0 - 2023-09-28

- Initial release
- Add support for webhook actions
