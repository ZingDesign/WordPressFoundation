# Zing Design Boilerplate WordPress theme

## Initial setup - Install all the toolses

### Install Compass

#### Run this command *only* **ONCE**

```
bundle
```

### Install Node modules, set up Foundation and Grunt

```
sudo npm install grunt-cli -g
sudo npm install bower -g
sudo npm install
gem install foundation
foundation new fnd
```

### Foundation update

```
cd fnd
foundation update
```

### Install front-end tools

- Font Awesome: Icon font
- Slick Carousel: Responsive content and image slider jQuery plugin

```
cd fnd
bower install https://github.com/FortAwesome/Font-Awesome.git
bower install https://github.com/kenwheeler/slick.git
```

### Grunt tasks

#### Process all JS, Sass, and fonts

```
grunt
```

#### Watch client JS & Sass

```
grunt watch
```


#### Process Sass

```
grunt css
```

#### Auto-prefix for Cross-browser compatibleness

```
grunt prefix
```

#### Copy icon fonts for Font Awesome and Slick

``` 
grunt copyFonts
```

#### Lazy command for Process and Watch

```
grunt w
```

#### Disable dev-mode

While in dev-mode, stylesheets and scripts are uncompressed for easier debuggingness. It is recommended that you do this before production for optimisation reasons.

In Gruntfile.js:

```
var devMode = false;
```

Stop any Grunt watchers, then run: 

```
grunt w
```

### Compass (manual config)

#### Development

``` 
compass clean && compass compile -e development && grunt prefix
```

#### Production

``` 
compass clean && compass compile -e production && grunt prefix
```