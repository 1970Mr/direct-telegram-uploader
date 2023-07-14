# Direct Telegram Uploader

Direct Telegram Uploader is a PHP script for uploading files to a Telegram group.

## Installation

1. Make sure you have PHP installed on your system. You can check if PHP is installed by running `php -v` in the terminal. If PHP is not installed, you can download and install it from the official PHP website: https://www.php.net/downloads.

2. Clone this repository to your local machine using the following command:

   ```bash
   git clone [<repository_url>](https://github.com/github-1970/direct-telegram-uploader)
   ```

3. Install the required dependencies using Composer. Make sure you have Composer installed globally. If not, you can download and install Composer from the official website: https://getcomposer.org/download/.

   ```bash
   composer install
   ```

4. Copy the `.env.example` file in the root directory of the project and rename it to `.env`:
   ```bash
   cp .env.example .env
   ```

5. Open the `.env` file and add the following lines:

   ```plaintext
   TELEGRAM_API_TOKEN=YOUR_TELEGRAM_API_TOKEN
   TELEGRAM_GROUP_ID=YOUR_TELEGRAM_GROUP_ID
   ```

   Replace `YOUR_TELEGRAM_API_TOKEN` and `YOUR_TELEGRAM_GROUP_ID` with your actual Telegram API token and group ID.

## Usage

To use the Direct Telegram Uploader script, follow these steps:

1. Open a terminal or command prompt and navigate to the directory where you cloned the repository.

2. Run the script by executing the following command:

   ```bash
   php send_files_to_telegram.php <folder_path>
   ```

   Replace `<folder_path>` with the path to the folder containing the files you want to send to the Telegram group.

   For example:

   ```bash
   php send_files_to_telegram.php /path/to/files/folder
   ```

   The script will start uploading the files to the Telegram group. Progress information and success messages will be displayed in the terminal.

   If the upload is successful, you will see a success message for each file uploaded. If there are any errors, you will see an error message indicating the issue.

## License

This project is licensed under the [MIT License](LICENSE).