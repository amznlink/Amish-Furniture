# Use the official Codespaces image as a base
FROM ghcr.io/github/vscode-dev-containers/codespaces-base:latest

# Install PHP
RUN sudo apt-get update && \
    sudo apt-get install -y php php-cli

# Set the default shell to bash
ENV SHELL /bin/bash
