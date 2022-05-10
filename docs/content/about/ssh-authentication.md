---
title: "SSH Authentication"
description: "SSH Authentication at a glance"
weight: 30
date: "2022-05-10"
lastmod: "2022-05-10"
---

SSH authentication is broadly categorized into two types: *password-based* and *public key authentication*.

## 1. Password-based authentication

In *password-based authentication*, the client sends an authentication request to the server which includes the encrypted username and password for the remote server. Upon receipt, the server decrypts the request and validates the credentials in plain text. Once verified, the client is notified of the authentication outcome.

## 2. Public key authentication

*Public key authentication* — also known as asymmetric encryption — uses a cryptographic key pair which comprises a private and public key on the client system. After generating the SSH key pair, both the public and private keys are saved on the client. The client then copies the public key to the remote server. So, how does the authentication really work? 

During authentication, the client system sends an authentication request to the remote server which includes a public key. The remote server then receives the request and checks if the public key matches the one copied to it. If the key is valid and matches the one on the server, the server then creates a secret message and encrypts it with the public key from the client. The message is then sent back to the client whereupon the private key decrypts the message. Because this is asymmetric encryption, only the client system can decrypt the message with the private key. Once the message is decrypted, the server acknowledges it and the authentication is successful.

Public key authentication is the more preferred authentication of the two. It is more convenient and secure than password-based authentication — and for good reasons. SSH keys are complex and difficult to crack thanks to the strong encryption algorithms used. In addition, only the user with the private key can access the remote system. 

When public key authentication is enabled, password authentication should be turned off so that only the private key alone can be used to authenticate with the remote system.