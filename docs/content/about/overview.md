---
title: "Overview"
description: "SSH Keys management"
weight: 20
date: "2022-03-01"
lastmod: "2022-03-01"
---

Secure Shell, or Secure Socket Shell — commonly abbreviated as SSH — is a [secure network protocol](https://www.ssh.com/ssh/protocol/) that allows users to securely authenticate to remote devices. SSH keys are used to access the [SSH protocol](https://www.ssh.com/ssh/protocol/). SSH leverages a pair of SSH keys to encrypt communication with a remote system. The key pair is cryptographic in nature and made up of a *public* and *private key*. The keys work in tandem to provide authentication between the client and the remote system. 

SSH keys grant users access to critical systems such as cloud and on-premise servers. Typically, these are systems that should only be accessed by authorized users, and no one else. A couple of methods exist for accessing the SSH protocol – passwords and SSH keys. It is not recommended to use passwords because their level of security is relatively low. SSH keys on the other hand can be a very secure method because they are made up of very long strings of [composite numbers and prime factors](https://www.linkedin.com/pulse/2048-bit-encryption-what-why-does-matter-srini-vasan). This is more challenging to crack than passwords, and is why SSH keys are favored over passwords for authenticating to the SSH protocol. 

Before we proceed further, let us draw a distinction between public and private keys.

## Private and Public SSH Keys

As the name suggests, the *private key* is only meant for the person who created it, and therefore strictly resides on the client system. It allows users to securely authenticate with the remote server, and should always be kept secret and never disclosed to anyone. In the wrong hands, the private key could be compromised and malicious users can use it to breach your systems. We cannot emphasize this enough: **the private key should never be revealed to anyone else**.

On the other hand, the *public key* can be freely shared with any server you wish to connect to without compromising your identity. It is used for encrypting data exchanged between the server and the client. The private key decrypts the messages sent from the remote server and a connection is established. On the remote system, the public key is saved in the `authorized_keys` file.
