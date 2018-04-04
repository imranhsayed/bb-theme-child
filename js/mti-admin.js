(function ( $ ) {
	var gmail = {

		fromName: '',
		fromEmail: '',
		toName: '',
		toEmail: '',
		sub: '',
		body: '',

		init: function () {
			gmail.mtiMainHandler();
			gmail.replyEmailHandler();
			$( 'body' ).on( 'click', '#mti-email-reply-btn', function () {
					var myString = gmail.fromEmail, emailTo;
						myString = myString.match( '\<(.*?)\>' );
					    emailTo = myString[1];
					$( '#mti-email_to' ).val( emailTo );
					$( '#mti-email_sub' ).val( 'Re: ' + gmail.sub );
					$( '.mti-reply-editor-area' ).html( gmail.body );
					$( '.close' ).click();
					$( '.mti-reply-email-container' ).removeClass( 'mti-reply-hidden' );
			} );
		},

		replyEmailHandler: function () {
			$( '#mti-compose-email-form' ).on( 'submit', function () {
				event.preventDefault();
				emailAjaxRequest();
				console.log( gmail.fromEmail );
			} );

			function emailAjaxRequest() {
				console.log( emaildata );
				var mailBody = $( '#mti-email-text-editor' ).val(),
					to = $( '#mti-email_to' ).val(),
					cc = $( '.mti_input_cc' ).val(),
					sub = $( '#mti-email_sub' ).val(),
					replyBody = gmail.body;

				console.log( mailBody );
				var request = $.post(
					emaildata.ajax_url,   // this url till admin-ajax.php  is given by functions.php wp_localoze_script()
					{
						action: 'mti_send_email',
						security: emaildata.ajax_nonce,
						to: to,
						from: gmail.fromEmail,
						cc: cc,
						sub: sub,
						msg_body: mailBody,
						reply_body: gmail.body
					},
					function( status ){
						console.log( status );  // result {success: true, data: {…}}data: data_recieved_from_js: {action: "my_ajax_hook", name: "Lawrence", profession: "actress"}hello_world: "hello"__proto__: Objectsuccess: true__proto__: Object
					}
				);

				request.done( function ( response ) {
					console.log( 'The server responded: ');
					console.log( response );
				} );
			}
		},

		mtiMainHandler: function () {
			var clientId = '169284166980-2h9cubjfkld331r2rvolj97o5ur7dta3.apps.googleusercontent.com';
			var apiKey = 'AIzaSyCvTvvAJBPwh4uGDo-l05aiOWB084azPos';
			var scopes =
				'https://www.googleapis.com/auth/gmail.readonly '+
				'https://www.googleapis.com/auth/gmail.send';

			$( '.mti-authenticate' ).on( 'click', mtiHandleLoginAndEmails );

			function mtiHandleLoginAndEmails() {
				handleClientLoad();
				document.querySelector( '.mti-gmail-searchbox input' ).value = '';
				$( '.mti-authenticate' ).off( 'click', mtiHandleLoginAndEmails );
			}

			$( '.mti-email-tbody' ).on( 'click', '.mti-sub-link', setFromAndToEmailVal );

			function setFromAndToEmailVal( event ) {
				var targetEl = event.target;
				gmail.fromEmail = targetEl.getAttribute( 'data-email-from' );
				gmail.sub = targetEl.getAttribute( 'data-email-sub' );
				$( '#reply-to' ).val( gmail.fromEmail );
				$( '#reply-subject' ).val( 'Re: ' + gmail.sub );
			}

			$( '.mti-form-sbt-btn' ).on( 'click', function () {
				sendEmail();
			} );

			$( '#mti-email-reply-button' ).on( 'click', function () {
				sendReply();
			} );


			function handleClientLoad() {
				gapi.client.setApiKey(apiKey);
				window.setTimeout(checkAuth, 1);
			}

			function checkAuth() {
				gapi.auth.authorize({
					client_id: clientId,
					scope: scopes,
					immediate: true
				}, handleAuthResult);
			}

			function handleAuthClick() {
				gapi.auth.authorize({
					client_id: clientId,
					scope: scopes,
					immediate: false
				}, handleAuthResult);
				return false;
			}

			function handleAuthResult(authResult) {
				if(authResult && !authResult.error) {
					loadGmailApi();
					$('#authorize-button').remove();
					$('.table-inbox').removeClass("hidden");
					$('#compose-button').removeClass("hidden");
					$( '.mti-gmail-searchbox' ).removeClass( 'hidden' );
					// $( '.mti-authenticate' ).on( 'click', mtiHandleLoginAndEmails );
				} else {
					$('#authorize-button').removeClass("hidden");
					$('#authorize-button').on('click', function(){
						handleAuthClick();
					});
					$( '.mti-authenticate' ).on( 'click', mtiHandleLoginAndEmails );
				}
			}

			function loadGmailApi() {
				gapi.client.load('gmail', 'v1', displayInbox);
			}

			$( '.mti-email-search-icon' ).on( 'click', function () {
				displayInbox();
				$( '.mti-email-tbody tr' ).remove();
			} );

			$( '.mti-gmail-searchbox input' ).keypress( function () {
				var key = event.which;
				// If enter key is pressed
				if ( 13 === key ) {
					$( '.mti-email-tbody tr' ).remove();
					displayInbox();
				}
			} );

			function displayInbox() {
				var searchInputVal = document.querySelector( '.mti-gmail-searchbox input' ).value,
					request;
				$( '.mti-email-tbody tr' ).remove();
				$( '.mti-authenticate' ).text( 'Inbox' );
				if ( searchInputVal ) {
					searchInputVal = 'from:' + searchInputVal;
					request = gapi.client.gmail.users.messages.list({
						'userId': 'me',
						'labelIds': 'INBOX',
						'maxResults': 30,
						'q': searchInputVal
					});
				} else  {
					request = gapi.client.gmail.users.messages.list({
						'userId': 'me',
						'labelIds': 'INBOX',
						'maxResults': 2
					});
				}
				request.execute(function(response) {
					$.each(response.messages, function() {
						var messageRequest = gapi.client.gmail.users.messages.get({
							'userId': 'me',
							'id': this.id
						});
						messageRequest.execute(appendMessageRow);
					});
				});
			}

			/**
			 * Get Attachments from a given Message.
			 * Sets a counter.
			 * Runs a loop and check if the message has an attachment ,
			 * if it does then executes the request and calls the call back function attachementCallBack() and
			 * passed filename, mimeType, attachment object ( containing base64 encoded attachment data and newArray containing
			 * array of file names in case if there are multiple attachment files
			 * The call back function attachementCallBack() is being called no. of times the attachment is there.
			 *
			 * @param  {String} userId User's email address. The special value 'me'
			 * can be used to indicate the authenticated user.
			 * @param  {String} message ID of Message with attachments.
			 * @param  {Function} callback Function to call when the request is complete.
			 */
			function getAttachments( userId, message, callback) {
				var parts = message.payload.parts,
					countItems = 0;
				console.log( message );
				var newArray = [];
				if ( ! parts ) {
				    return;
				}
				for (var i = 0; i < parts.length; i++) {
					part = parts[i];
					if ( part.filename && part.filename.length > 0) {
						newArray.push( part.filename );
						var attachId = part.body.attachmentId;
						var request = gapi.client.gmail.users.messages.attachments.get({
							'id': attachId,
							'messageId': message.id,
							'userId': userId
						});
						request.execute(function( attachment ) {
							console.log( attachment );
							var fileName = newArray[ countItems ];
							callback( fileName, part.mimeType, attachment, newArray );
							countItems ++;
						});
					}
				}
			}

			function appendMessageRow( message ) {

				var sub = getHeader(message.payload.headers, 'Subject'),
					emailWithName = getHeader(message.payload.headers, 'From'),
					emailFrom = emailWithName.match( '\<(.*?)\>' );
				emailFrom = emailWithName;
				getAttachments( 'me', message, attachmentCallBack );

				if ( ! sub ) {
					sub = 'No Subject';
				}
				$('.table-inbox tbody').append(
					'<tr>\
					  <td>'+getHeader(message.payload.headers, 'From')+'</td>\
            <td>\
              <a href="#message-modal-' + message.id +
					'" class="mti-sub-link" data-toggle="modal" id="message-link-' + message.id+'" data-email-from="' + emailFrom + '" data-email-sub="' + sub + '">' +
					sub +
					'</a>\
				  </td>\
				  <td>'+getHeader(message.payload.headers, 'Date')+'</td>\
          </tr>'
				);
				var reply_to = (getHeader(message.payload.headers, 'Reply-to') !== '' ?
					getHeader(message.payload.headers, 'Reply-to') :
					getHeader(message.payload.headers, 'From')).replace(/\"/g, '&quot;');

				// var reply_subject = 'Re: '+getHeader(message.payload.headers, 'Subject').replace(/\"/g, '&quot;');
				var reply_subject = sub;

				$('body').append(
					'<div class="modal fade" id="message-modal-' + message.id +
					'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
				  <div class="modal-dialog modal-lg">\
					<div class="modal-content">\
					  <div class="modal-header">\
						<button type="button"\
								class="close"\
								data-dismiss="modal"\
								aria-label="Close">\
						  <span aria-hidden="true">&times;</span></button>\
						<h4 class="modal-title" id="myModalLabel">' +
					getHeader(message.payload.headers, 'Subject') +
					'</h4>\
				  </div>\
				  <div class="modal-body">\
					<iframe id="message-iframe-'+message.id+'" srcdoc="<p>Loading...</p>">\
                  </iframe>\
                </div>\
                <div class="modal-footer">\
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                  <div class="btn btn-primary" id="mti-email-reply-btn">Reply</div>\
                </div>\
              </div>\
            </div>\
          </div>'
				);

				$('#message-link-'+message.id).on('click', function(){
					var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
					$('body', ifrm).html(getBody(message.payload));
					gmail.body = getBody(message.payload);
				});

				/**
				 * The getAttachments function callback , this function calls the getAttachmentBody() and gets the downloadable link element,
				 * and appends to the iframe body of the email
				 *
				 * @param filename
				 * @param mimeType
				 * @param attachment
				 */
				function attachmentCallBack( filename, mimeType, attachment ) {
					var ifrm = $('#message-iframe-'+ message.id)[0].contentWindow.document;
					var link = getAttachmentBody( attachment, filename, mimeType );
					$( link ).insertAfter( $( ifrm.body ) );
				}
			}

			/**
			 * Creates the link element and returns it so that it can be appended to the dom by calling convertDataIntoDownloadableFile()
			 *
			 * @param attachment Object containing the data of attachment in base64 encoded form
			 * @param filename
			 * @param mimeType
			 * @return {*} link
			 */
			function getAttachmentBody( attachment, filename, mimeType ) {
				var encodedBody = '';
				encodedBody = attachment.result.data;

				encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
				content = encodedBody;
				var link = document.createElement( 'a' ),
					divEl = document.createElement( 'div' );
				link.setAttribute( 'id', 'downloadlink' );
				link.setAttribute( 'download', filename );
				link.textContent = 'Download Attachment: ' + filename + ' ';
				divEl.setAttribute( 'class', 'mti-attachement-container' );
				divEl.appendChild( link );


				link = convertDataIntoDownloadableFile( encodedBody, filename, mimeType, link );
				// console.log( link );
				content = divEl;

				if ( content ) {
					return content;
				} else {
					return '';
				}
			}

			/**
			 * Creates a link for downloading attachment,
			 * by decoding base64 data( using atob() )  and then converting into blob data
			 * and then creating a url using that blob data ( using window.URL.createObjectURL() )
			 * and setting the url to the url of that downloadable link being returned.
			 *
			 * @param encodedBody base64 encoded data
			 * @param filename
			 * @param mimeType
			 * @param link
			 * @return {*} link
			 */
			function convertDataIntoDownloadableFile( encodedBody, filename, mimeType, link ) {
				var textFile = null,
					makeTextFile = function ( encodedBody, mimeType ) {
						var data = b64toBlob( encodedBody, mimeType);
						function b64toBlob(b64Data, contentType, sliceSize) {
							contentType = contentType || '';
							sliceSize = sliceSize || 512;

							var byteCharacters = atob(b64Data);
							var byteArrays = [];

							for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
								var slice = byteCharacters.slice(offset, offset + sliceSize);

								var byteNumbers = new Array(slice.length);
								for (var i = 0; i < slice.length; i++) {
									byteNumbers[i] = slice.charCodeAt(i);
								}

								var byteArray = new Uint8Array(byteNumbers);

								byteArrays.push(byteArray);
							}
							// console.log(byteArrays);

							var blob = new Blob(byteArrays, {type: contentType});
							return blob;
						}

						// If we are replacing a previously generated file we need to
						// manually revoke the object URL to avoid memory leaks.
						if ( textFile !== null ) {
							window.URL.revokeObjectURL( textFile );
						}

						textFile = window.URL.createObjectURL( data );

						return textFile;
					};
					link.href = makeTextFile( encodedBody, mimeType );
					return link;
					// link.style.display = 'block';
			}

			$( '#btnLoad' ).on( 'click', function () {
					if ( ! window.File || !window.FileReader || ! window.FileList || ! window.Blob ) {
						alert('The File APIs are not fully supported in this browser.');
						return;
					}

					input = document.getElementById('fileinput');
					if ( ! input ) {
						alert( "Um, couldn't find the fileinput element." );
					}
					else if ( ! input.files ) {
						alert("This browser doesn't seem to support the `files` property of file inputs.");
					}
					else if ( ! input.files[0] ) {
						alert("Please select a file before clicking 'Load'");
					}
					else {
						var counter = 0,
							fileDataArray = [],
							inputObj = input.files,
							inputObjLength = inputObj.length,
							fileListObj = $( '#fileinput' ).prop('files'),
							fileUrl;
						getUploadedImgData( inputObj );

						/**
						 * Reads the file data into an file url which contains the mimetype and data in base64 format using FileReader()
						 * Split the URL to get the mime type and base64data for the upload attachment.
						 * Then it pushes the object containing mime type, base64 data, file name , file size into an array called fileDataArray.
						 */
						function getUploadedImgData() {
							var fileListObject = '';
							file = inputObj[ counter ];
							fileListObject = fileListObj[ counter ];
							fr = new FileReader();
							fr.onload = function () {
								var block, mimeType, base64Data,
									fileDataObj = {};
								fileUrl = fr.result;
								block = fileUrl.split(";");
								// Get the content type
								mimeType = block[0].split(":")[1];
								// get the real base64 content of the file
								base64Data = block[1].split(",")[1];
								fileDataObj.mimetype = mimeType;
								fileDataObj.base64data = base64Data;
								fileDataObj.name = fileListObject.name;
								fileDataObj.size = fileListObject.size;
								fileDataArray.push( fileDataObj );
								counter ++;

								// Call the getUploadedImgData() as many times as the no. of attachment.
								if ( inputObjLength > counter ) {
									getUploadedImgData();
								}
								if ( ( inputObjLength ) === counter ) {
									appendAttachmentFileNames( fileDataArray );
									$( '.mti-attachment-data' ).append( base64Data );
								}
							};
							fr.readAsDataURL( file );
						}
					}
				// console.log( fileDataArray );
			} );

			/**
			 * Appends the attachment file names to the email body.
			 * @param fileDataArray
			 */
			function appendAttachmentFileNames( fileDataArray ) {
				console.log( fileDataArray );
				var fileNameContainer = document.querySelector( '.mti-attachment-filename-container' );
				console.log( fileDataArray.length );
				// if ( ( ! fileDataArray ) && ( Array.isArray( fileDataArray ) ) ) {
					for ( var i = 0; i < fileDataArray.length; i++ ) {
						var fileName = fileDataArray[ i ].name,
							liEl = document.createElement( 'li' );
							liEl.setAttribute( 'class', 'mti-filename-li' );
							liEl.textContent = fileName;
							console.log( liEl );
						fileNameContainer.appendChild( liEl );
					}
				// }
			}

			function sendEmail()
			{
				$('#send-button').addClass('disabled');

				sendMessage(
					{
						'To': $('#compose-to').val(),
						'Subject': $('#compose-subject').val()
					},
					$('#compose-message').val(),
					composeTidy
				);

				return false;
			}

			function composeTidy()
			{
				$('#compose-modal').modal('hide');

				$('#compose-to').val('');
				$('#compose-subject').val('');
				$('#compose-message').val('');

				$('#send-button').removeClass('disabled');
				console.log( 'message send complete' );
			}

			function sendReply()
			{
				$('#reply-button').addClass('disabled');

				sendMessage(
					{
						'To': $('#reply-to').val(),
						'Subject': $('#reply-subject').val(),
						'In-Reply-To': $('#reply-message-id').val()
					},
					$('#reply-message').val(),
					replyTidy
				);
				console.log( $('#reply-to').val() );

				return false;
			}

			function replyTidy()
			{
				$('#reply-modal').modal('hide');

				$('#reply-message').val('');

				$('#reply-button').removeClass('disabled');
			}

			function fillInReply(to, subject, message_id)
			{
				$('#reply-to').val(to);
				$('#reply-subject').val(subject);
				$('#reply-message-id').val(message_id);
			}

			function sendMessage(headers_obj, message, callback)
			{
				var email = '';

				for(var header in headers_obj)
					email += header += ": "+headers_obj[header]+"\r\n";

				email += "\r\n" + message;

				var sendRequest = gapi.client.gmail.users.messages.send({
					'userId': 'me',
					'resource': {
						'raw': window.btoa(email).replace(/\+/g, '-').replace(/\//g, '_')
					}
				});

				return sendRequest.execute(callback);
			}


			function getHeader(headers, index) {
				var header = '';
				$.each(headers, function(){
					if(this.name.toLowerCase() === index.toLowerCase()){
						header = this.value;
					}
				});
				return header;
			}

			function getBody(message) {
				var encodedBody = '';
				if(typeof message.parts === 'undefined')
				{
					encodedBody = message.body.data;
				}
				else
				{
					encodedBody = getHTMLPart(message.parts);
				}
				encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
				return decodeURIComponent(escape(window.atob(encodedBody)));
			}

			function getHTMLPart(arr) {
				for(var x = 0; x <= arr.length; x++)
				{
					if(typeof arr[x].parts === 'undefined')
					{
						if(arr[x].mimeType === 'text/html')
						{
							return arr[x].body.data;
						}
					}
					else
					{
						return getHTMLPart(arr[x].parts);
					}
				}
				return '';
			}
		}
	};
	gmail.init();
	
	$( '.parent' ).on( 'click', '.target', function () {
		
	} )
})( jQuery );

