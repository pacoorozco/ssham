FROM ubuntu:20.04

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG USER=admin
ARG PASSWORD=docker

# Run ssh server on the DNS server.
RUN apt-get update && apt-get install -y openssh-server
RUN mkdir -p /run/sshd

# Creates the user that ProBIND will use to access to the DNS server.
RUN useradd --home-dir /home/${USER} --create-home ${USER} && \
    echo "${USER}:${PASSWORD}" | chpasswd
COPY --chown=${USER}:${USER} ./docker/ssh/authorized_keys /home/${USER}/.ssh/authorized_keys

# Configures SSH to enable SFTP service.
COPY ./docker/ssh/sshd/*.conf /etc/ssh/sshd_config.d/
RUN sed -i "s/__DOCKER_USER__/${USER}/" /etc/ssh/sshd_config.d/sftp.conf

EXPOSE 22

# -D in CMD below prevents sshd from becoming a daemon. -e is to log everything to stderr.
CMD ["/usr/sbin/sshd", "-D", "-e"]

