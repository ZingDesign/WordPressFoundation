# Zing Design Boilerplate WordPress theme

## Initial setup - Install all the toolses

### Install Compass

Compass v1.0.0 is all weird and don't work right, rather than fixing the stylesheets, I suggest reverting back an older, more reliable version

```
gem uninstall compass (uninstall version 1.0.0)
gem install compass -v 0.12.7
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

#### Copy icon fonts for Font Awesome and Slick

``` 
grunt copyFonts
```

#### Lazy command for Process and Watch

```
grunt w
```