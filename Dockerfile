FROM php

RUN apt-get update
    && apt-get install -y inotify-tools
