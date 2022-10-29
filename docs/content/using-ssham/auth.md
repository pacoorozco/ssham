---
title: "Authorization"
description: "Roles and capabilities"
weight: 40
date: "2022-05-10"
lastmod: "2022-05-10"
---

There are 4 roles that you can assign to users. Each role provides different [capabilities](#capabilities) to manage ssham's assets.

{{< hint type=tip >}}
**[Principle of least privilege](https://en.wikipedia.org/wiki/Principle_of_least_privilege)**\
Every privileged user of the system should operate using the least amount of privilege necessary to complete the job.
{{< /hint >}}

Role | Keys and group of keys | Hosts and group of hosts | Rules | Users
--- | :---: | :---: | :---: | :---:
Super Admin | Edit | Edit | Edit | Edit
Admin | Edit | Edit | Edit | View
Operator | Edit | Edit | View | View
Auditor | View | View | View | View

## Capabilities

* **Edit** includes viewing, creating, modifying and deleting asset capabilities. 
* **View** only includes viewing assets capability.

## API authentication

In order to use the ssham API you need to create a **Personal access token**. 

To create a new token go to: User management > Edit > Personal access tokens.

{{< hint type=important >}}
**API authorization**\
The token will have the same capabilities than the user who is owning the token.
{{< /hint >}}

## Default credentials
Some users are created by default:

Role | Username | Password | It can modify...
---|---|---|---
Super Admin | superadmin | superadmin | Everything
Admin | admin |admin | Everything, except Users and Settings
Operator | operator | operator | Keys and Hosts
Auditor | auditor | auditor | Nothing, READ-ONLY role

