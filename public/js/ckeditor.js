
class MyUploadAdapter {

    constructor( loader ) {
        // The file loader instance to use during the upload.
        this.loader = loader;
    }

    // Starts the upload process.
    upload() {
        return new Promise( function (resolve, reject ) {

            const xhr = new XMLHttpRequest();

            // Note that your request may look different. It is up to you and your editor
            // integration to choose the right communication channel. This example uses
            // a POST request with JSON as a data structure but your configuration
            // could be different.
            xhr.open( 'POST', UploadURL, true );
            xhr.responseType = 'json';

            xhr.addEventListener( 'load', function() {
                const response = xhr.response;

                // This example assumes the XHR server's "response" object will come with
                // an "error" which has its own "message" that can be passed to reject()
                // in the upload promise.
                //
                // Your integration may handle upload errors in a different way so make sure
                // it is done properly. The reject() function must be called when the upload fails.
                if ( !response || response.error ) {
                    return reject( response && response.error ? response.error.message : genericErrorText );
                }

                // If the upload is successful, resolve the upload promise with an object containing
                // at least the "default" URL, pointing to the image on the server.
                // This URL will be used to display the image in the content. Learn more in the
                // UploadAdapter#upload documentation.
                resolve( {
                    default: response.url
                });

//                    this._initRequest();
//                    this._initListeners( resolve, reject );
//                    this._sendRequest();

                const data = new FormData();
                data.append( 'upload', this.loader.file );

                // Important note: This is the right place to implement security mechanisms
                // like authentication and CSRF protection. For instance, you can use
                // XMLHttpRequest.setRequestHeader() to set the request headers containing
                // the CSRF token generated earlier by your application.

                // Send the request.
                this.xhr.send( data );
            });
        });
    }

    // Aborts the upload process.
    abort() {
        if ( this.xhr ) {
            this.xhr.abort();
        }
    }

    // Initializes XMLHttpRequest listeners.
//            _initListeners( resolve, reject ) {
//                const xhr = this.xhr;
//                const loader = this.loader;
//                const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;
//
//                xhr.addEventListener( 'error', function () { reject( genericErrorText )});
//                xhr.addEventListener( 'abort', function () { reject() });
//                xhr.addEventListener( 'load', function() {
//                    const response = xhr.response;
//
//                // This example assumes the XHR server's "response" object will come with
//                // an "error" which has its own "message" that can be passed to reject()
//                // in the upload promise.
//                //
//                // Your integration may handle upload errors in a different way so make sure
//                // it is done properly. The reject() function must be called when the upload fails.
//                if ( !response || response.error ) {
//                    return reject( response && response.error ? response.error.message : genericErrorText );
//                }
//
//                // If the upload is successful, resolve the upload promise with an object containing
//                // at least the "default" URL, pointing to the image on the server.
//                // This URL will be used to display the image in the content. Learn more in the
//                // UploadAdapter#upload documentation.
//                resolve( {
//                    default: response.url
//                } );
//            } );
//
//                // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
//                // properties which are used e.g. to display the upload progress bar in the editor
//                // user interface.
//                if ( xhr.upload ) {
//                    xhr.upload.addEventListener( 'progress', function(evt) {
//                        if ( evt.lengthComputable ) {
//                            loader.uploadTotal = evt.total;
//                            loader.uploaded = evt.loaded;
//                        }
//                    });
//                }
//            }

    // Prepares the data and sends the request.
//            _sendRequest() {
//                // Prepare the form data.
//                const data = new FormData();
//                data.append( 'upload', this.loader.file );
//
//                // Important note: This is the right place to implement security mechanisms
//                // like authentication and CSRF protection. For instance, you can use
//                // XMLHttpRequest.setRequestHeader() to set the request headers containing
//                // the CSRF token generated earlier by your application.
//
//                // Send the request.
//                this.xhr.send( data );
//            }
}

function MyCustomUploadAdapterPlugin( editor ) {
    editor.plugins.get( 'FileRepository' ).createUploadAdapter = function( loader ) {
        // Configure the URL to the upload script in your back-end here!
        return new MyUploadAdapter( loader );
    };
}

DecoupledEditor
    .create( document.querySelector( '#editor1' ) , {
        alignment: {
            options: [ 'left', 'right', 'justify', 'center' ]
        },
        extraPlugins: [ MyCustomUploadAdapterPlugin ]
    }).then(function (editor) {

    const toolbarContainer = document.querySelector( '#toolbar-container' );
    toolbarContainer.appendChild( editor.ui.view.toolbar.element );
    editor.execute( 'alignment', { value: 'justify' } );
});