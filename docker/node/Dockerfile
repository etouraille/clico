FROM node:14

RUN apt-get -qq update && apt-get -qq install -y  --no-install-recommends \
      git \
      curl \
      vim \
      build-essential \
      libfontconfig1 \
    && rm -rf /var/lib/apt/lists/*


RUN npm install -g @angular/cli


WORKDIR /src
