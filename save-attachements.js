(function ($) {

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
		var newArray = [];
		for ( var i = 0; i < parts.length; i++ ) {
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
					var fileName = newArray[ countItems ];
					callback( fileName, part.mimeType, attachment );
					countItems ++;
				});
			}
		}
	}

	getAttachments( 'me', message, attachmentCallBack );

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
		encodedBody = attachment.result.data;
		encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
		var link = document.createElement( 'a' );
		link.setAttribute( 'id', 'downloadlink' );
		link.setAttribute( 'download', filename );
		link.textContent = 'Download Attachment: ' + filename + ' ';
		link = convertDataIntoDownloadableFile( encodedBody, filename, mimeType, link );

		if ( link ) {
			return link;
		} else {
			return '';
		}
	}

	/**
	 * The getAttachments function callback , this function calls the getAttachmentBody() and gets the downloadable link element,
	 * and appends to the iframe body of the email
	 *
	 * @param filename
	 * @param mimeType
	 * @param attachment
	 */
	function attachmentCallBack( filename, mimeType, attachment ) {
		var ifrm = $( '#message-iframe-'+ message.id )[0].contentWindow.document;
		var link = getAttachmentBody( attachment, filename, mimeType );
		$( link ).insertAfter( $( ifrm.body ) );
	}
})(jQuery);