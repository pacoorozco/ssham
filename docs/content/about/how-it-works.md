---
title: "How it works"
description: "The ssham"
weight: 70
date: "2022-05-10"
lastmod: "2022-05-10"
---


SSHAM runs on top of a bastion host. A bastion host is a server that has SSH access to other servers. 

The bastion host uses a SSH keypair to connect to the remote hosts. Every time that the bastion host wants to connect a remote server, it will use the private key. The remote server will authenticate the bastion host using the public key which was added the the remote's `authorized_keys` file.

You must deploy the bastion's public key in each server that you want to manage with ssham. You can use puppet, ansible... to automate this step.

Once the bastion host has access to all the servers, you can use ssham to provide access to other users through their public keys. 

ssham allows you to create and import SSH keys. These SSH keys could be grouped in keys groups. 

The managed hosts could be grouped in hosts groups.

And finally you can provide (or deny) access to a group of servers using a groups of keys, this is a rule. 

Once you have defined your rules, you need to sync all the servers by configuring the `authorized_keys` file on the remote servers with the group of keys that has access granted. You can do it easily with the ssham provided command:

`php artisan ssham:send`

This command will connect to the remote host and update the content of the `authorized_keys` file, which has all the SSH public keys allowed to login. 