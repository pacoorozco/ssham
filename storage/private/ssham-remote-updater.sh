#! /bin/sh

#************************************************#
#   ssham-remote-updater.sh                      #
#   written by Paco Orozco                       #
#   October 12, 2008                             #
#                                                #
#   This script is part of SSHAM project         #
#                                                #
#   SSH Access Manager (SSHAM) is a              #
#   comprehensive access security management     #
#   platform that permits IT professionals to    #
#   easily establish and maintain an             #
#   enterprise-wide SSH access security solution #
#   from a central location.                     #
#                                                #
#   http://sourceforge.net/projects/ssham        #
#************************************************#

# test, if arguments are correct.
if [ "$#" -ne "5" ]
then
    # one or more parameters are missing.
    exit 1
fi

# argument assigments.
ACTION=$1          # actions: new, update, uninstall
MIXED_MODE=$2      # mixed mode, merge files
AUTHORIZED_KEYS=$3 # $$config{'ssham.authorized_keys'}
NON_SSHAM_FILE=$4  # $$config{'ssham.non_ssham_file'}
SSHAM_FILE=$5      # $$config{'ssham.ssham_file'}

# to avoid errors, we assure that theese files exists
touch ${AUTHORIZED_KEYS} ${NON_SSHAM_FILE}

# test if it's the first time we do an update.
if [ "${ACTION}" = "new" ]
then
    # it's a new added host, so backup original authorized_keys
    cp -f ${AUTHORIZED_KEYS} ${NON_SSHAM_FILE}
    sort ${AUTHORIZED_KEYS} -o ${AUTHORIZED_KEYS}
    sort ${SSHAM_FILE} -o ${SSHAM_FILE}
    comm -2 -3 ${AUTHORIZED_KEYS} ${SSHAM_FILE} > ${NON_SSHAM_FILE}
fi

if [ "${ACTION}" = "uninstall" ]
then
    # we have to restore original authorized_keys
    cp -f ${NON_SSHAM_FILE} ${AUTHORIZED_KEYS}
    # ... then remove all ssham files
    rm -f ${NON_SSHAM_FILE} ${SSHAM_FILE}
    # ... and finally, delete myself.
    rm -f $0
    exit 0
fi

# print a header to generated ${AUTHORIZED_KEYS}
{
echo "###############################################################"
echo "#        DO NOT EDIT THIS FILE - DO NOT EDIT THIS FILE        #"
echo "#                                                             #"
echo "#  This file is generated automatically by SSHAM, if you need #"
echo "#  to modify a SSH key not maintained by SSHAM, please put it #"
echo "#  on: ${NON_SSHAM_FILE}   #"
echo "###############################################################"
} > ${AUTHORIZED_KEYS}

# if MIXED MODE we will merge files,
# otherwise we remove existing keys.
if [ "$MIXED_MODE" = "true" ]
then
    cat ${SSHAM_FILE} ${NON_SSHAM_FILE} >> ${AUTHORIZED_KEYS}
    exit $?
else
    cat ${SSHAM_FILE} >> ${AUTHORIZED_KEYS}
    exit $?
fi

# EOF
