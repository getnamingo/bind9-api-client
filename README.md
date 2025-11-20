# bind9-api-client
The bind9-api-client is a PHP client library designed to interact with the [bind9-api-server](https://github.com/getnamingo/bind9-api-server) or the [bind9-api-server-sqlite](https://github.com/getnamingo/bind9-api-server-sqlite).

## Installation Guide (Ubuntu 22.04/Ubuntu 24.04/Debian 12)

### 1. Install the required packages:

```bash
apt install -y curl software-properties-common ufw
add-apt-repository ppa:ondrej/php
apt update
apt install -y bzip2 composer git net-tools php8.3 php8.3-cli php8.3-common php8.3-curl php8.3-fpm php8.3-intl php8.3-mbstring php8.3-opcache php8.3-xml unzip
```

### 2. Download BIND9 API:

Clone the project repository in the directory to run the API client from:

```bash
git clone https://github.com/getnamingo/bind9-api-client /var/www/api
```

Install the required packages via Composer:

```bash
cd /var/www/api
composer Install
```

Explore `client_example.php` for example on how to use the api client.

## Support

Your feedback and inquiries are invaluable to our evolutionary journey. If you need support, have questions, or want to contribute your thoughts:

- **Email**: Feel free to reach out directly at [help@namingo.org](mailto:help@namingo.org).

- **Discord**: Or chat with us on our [Discord](https://discord.gg/97R9VCrWgc) channel.
  
- **GitHub Issues**: For bug reports or feature requests, please use the [Issues](https://github.com/getnamingo/bind9-api-client/issues) section of our GitHub repository.

## Acknowledgements

Special thanks to:
- **ChatGPT** for invaluable assistance with code and text writing.

## Licensing

Namingo is licensed under the MIT License.