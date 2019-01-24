#!/bin/bash
for (( ; ; ))
do
  wget -qO- http://magemore.com/cron/ &> /dev/null &
  sleep 30s
done
