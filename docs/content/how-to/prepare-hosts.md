---
title: "prepare your hosts"
description: "How to prepare your managed servers"
weight: 60
date: "2022-05-10"
lastmod: "2022-05-10"
---

ssham runs on top of a bastion host. A bastion host is a server that has SSH access to all the managed hosts. 

In order to grant access to the bastion host, you need a SSH keypair.

The private key of the SSH keypair will be used by the bastion host to connect to the managed hosts. This key should be kept veru careful because it provides access to all the managed hosts. 

The public key, instead, will be used by the remote hosts to authenticate the bastion host. This key should be deployed in the `authorized_keys` file in each remote host that will be managed using ssham.

In order to deploy this keys you can use tools like puppet, ansible...

Once you have deployed the bastion's public key in all the servers, they can be managed by ssham.

