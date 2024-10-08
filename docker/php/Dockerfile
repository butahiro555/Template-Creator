# ベースイメージの設定
FROM php:8.3-fpm

# 必要なパッケージのインストールと一括アップデート
RUN apt-get update && \
    apt-get install -y \
    vim \
    zip \
    unzip \
    curl \
    procps \
    zlib1g \
    libexpat1 \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Redis extension のインストール
RUN pecl install redis && docker-php-ext-enable redis

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www/html

# storageディレクトリを作成して権限設定
RUN mkdir -p /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/storage