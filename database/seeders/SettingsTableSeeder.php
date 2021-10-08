<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        setting()->set([
            /*
             * Where I will put authorized keys on remote hosts?
             */
            'authorized_keys' => '.ssh/authorized_keys',

            /*
             * Bastion Host Private Key, used to access other hosts
             * SSHAM will use this key to access to remote hosts.
             * ATTENTION: It's very important to keep safe this key.
             */
            'private_key' => '-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEA1rny3MS82I+Zfir8ZSinvzOPnuBs7WeGYNlD2tVIPcJuQwdo
R9weyko/nsNszQSQxIB5MbDUlt4r2ah98rJroqo2mGajKzZcZxS3kVk7hSaN+W8x
BGuG5hHnuaTajw2CjDSZ20UXSZ5I1E5clSXO53hBc1gRJiFfwmgcpsdnYf5SGSVC
MweYimamxJwa49gOD7BMvmTSB2M3B1dqhFpo2owjJk34no5YhyBgv+lZ3scGt1Bm
zuYfvUO9rI+czrBN6bSjd1kp2RxxhuANNyYBgpVfy7+9iXY/IvRbROh1ybkI4NHQ
9RvDF0c+XjkmEP7HpNdbb+lbIVEbSm0RxDm7eQIDAQABAoIBAE6/pVzn3iZCC9Xk
p+nljhemLj7jKa+rbvHn3GXOII9d1hhJCCqKhNRFhK2SGD3cNceLImdh5aVq14Qc
sgwm+SiP6jPcOG0po5u+UlBA/H4plJns+HoZgUCxQl2oIuuh1cXbqK1s2kyKe0U5
c+TTF912EfLeUnO8e6UNj6xupvCdqyj4D+/jm9wQtxS60kAfwVXpqGMv5Bi19Pcd
mfX79k0lFASlOE6n6wyRd3B0aDP9MkhWv1MKc3m7Mx9ncerAee1WiGmOXtKxrlii
8NT+0Vnk/4/2266eEr/O1j9rnhI2mMuSU3JaYa+WcmQgjYn8MCkiZQ2ls/wxMmF2
+kGaA7ECgYEA/qo2SDn+AkwuOWAXoCG9N/+K/SnblV9eqcld/azDtAL/8LA/cxbk
57xTVQUjg6SF4bm5h5/ckFZWmuOuTBGLq4EGr6GeNCENGfpp8wOwChuMvckuNWft
dAchNO3F7mcHc1C/M3nMknw3ki3VqdZDiDuZIEe+O3i1BRGzfyMVUTUCgYEA19oi
gfTTDkYubKG1lNqlHW3gM/7U2Kg1fIpThbTQRdL2r3c/Qv5dyeprLuEQtRD823BV
bJcnkc6pQfxvrM4d6AQbSf2vXgvQqN3gI9oELTnIsyteVeUqlIiWgyQV3ry97cYP
4KwliGn6uNjbspDWjj9TMMWH6NwS9T/QklB9LbUCgYBAOI5t0moTDEEP6QKsODXu
fDwBsd+VILLPoF9ajInhyGvgt6GYlWpuYhiltSEKnp/Al8SIxjCqGYvHjakzt60A
OA6Glfw+ayNRrMuxI/nt4pqwdZ54i1ffTbj7Dwc+Iq9HTlKZWZrUSgz9iR97r/Ph
1B2+fTkk6EDGIXuhkShcDQKBgGQAASCn5eelB/j36gHuUbSSpN4x3zYIp87s3skA
/cR7eiHMYxiMc7MJ0WJEjtLz7dFT9595X7GjRrL1Wl96lkxZKrNavhYZ7y9sq1yw
smKpdJvdKbUtmE58AQ78ds1cL7mqmsyBtFnAj5F3lkDh7SS1nbroqJBu9LF+QQio
FXZJAoGAeO3ixxEgnfAi9KRLclX8CG651ue4PhldllO82z4y1C8BIUD1SrQEIFU7
BBHAb40w/ou64RHttUVGSzNr7vXceadp57RVWqnMpQiWKQX3iDnxa+CCjIwyeKPg
c6i7uxhddb2j2GasjwJS0+KCE/csVWZ617lLWT0+U5SK7Aatjes=
-----END RSA PRIVATE KEY-----',

            /*
             * Bastion Host Public key, used to distribute on remote hosts
             */
            'public_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDWufLcxLzYj5l+KvxlKKe/M4+e4GztZ4Zg2UPa1Ug9wm5DB2hH3B7KSj+ew2zNBJDEgHkxsNSW3ivZqH3ysmuiqjaYZqMrNlxnFLeRWTuFJo35bzEEa4bmEee5pNqPDYKMNJnbRRdJnkjUTlyVJc7neEFzWBEmIV/CaBymx2dh/lIZJUIzB5iKZqbEnBrj2A4PsEy+ZNIHYzcHV2qEWmjajCMmTfiejliHIGC/6Vnexwa3UGbO5h+9Q72sj5zOsE3ptKN3WSnZHHGG4A03JgGClV/Lv72Jdj8i9FtE6HXJuQjg0dD1G8MXRz5eOSYQ/sek11tv6VshURtKbRHEObt5 root@ssham',

            /*
             * Where must I put temporary files?
             */
            'temp_dir' => '/tmp',

            /*
             * Time (seconds) to wait before a SSH connection ends (timed out)
             */
            'ssh_timeout' => '5',

            /*
             * Port to use as SSH
             */
            'ssh_port' => '22',

            /*
             * SSHAM will generate an authorized_keys for every remote host,
             * if there is any previous keys, SSHAM will remove it.
             * The ONLY way to deal with keys will be SSHAM, unless you use
             * MIXED MODE, se below.
             *
             * In MIXED MODE, SSHAM will merge, existing keys on remote hosts
             * with keys managed by SSHAM. It allows to put non SSHAM keys that
             * will not be removed by SSHAM.
             */
            'mixed_mode' => true,

            /*
             * This is the file that SSHAM will generate on remote hosts, this
             * file contains generated keys by SSHAM.
             */
            'ssham_file' => '.ssh/authorized_keys-ssham',

            /*
             * This is the file containing keys not managed by SSHAM. If you
             * want to use it, please put 'mixed_mode = 1'. Otherwise it's ignored.
             */
            'non_ssham_file' => '.ssh/authorized_keys-non-ssham',

            /*
             * Tools to use to deploy SSH
             */
            'cmd_remote_updater' => '.ssh/ssham-remote-updater.sh',
        ]);
    }
}
