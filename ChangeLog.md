# Change Log

### 1.2.0 - 2018/10/23
- Changed to display the HTML output for the `302` status.
- Enabled disk cache of PhantomJS.
- Added the `file-type` sub-parameter for the `screenshot` output type. 
- Added the `output-encoding` parameter.
- Added the `load-images` parameter which decides whether to load images.
- Added the ability to set a random user agent by giving `random` to the `user-agent` parameter. 
- Changed the behavior of the default `user-agent` parameter, which assigns the client user agent.

### 1.1.1 - 2018/10/22
- Added a header item `Access-Control-Allow-Origin: *` for JavaScript Ajax calls.  

### 1.1.0 - 2018/10/21
- Added the `header` query parameter to set additional HTTP header items. 
- Added the ability to set a random user agent when not given. 
- Added the `user-agent` query parameter to set a user agent.
- Added support for `POST` requests.   

### 1.0.0 - 2018/10/20
- Released.