<?php
/**
 * Custom template for loading gmail
 *
 * @package bb-theme-child
 */
echo '
	<button class="mti-authenticate">Sigin</button><div class="container mti-email-wraps">

      <a href="#compose-modal" data-toggle="modal" id="compose-button" class="btn btn-primary pull-right hidden">Compose</a>
		<div class="mti-gmail-searchbox hidden"><label for=""><i class="fa fa-search mti-email-search-icon" aria-hidden="true"></i><input type="text" placeholder="Search Email From"></label></div>
      <button id="authorize-button" class="btn btn-primary hidden">Authorize</button>

      <table class="table table-striped table-inbox hidden">
        <thead>
          <tr>
            <th>From</th>
            <th>Subject</th>
            <th>Date/Time</th>
          </tr>
        </thead>
        <tbody class="mti-email-tbody"></tbody>
      </table>
    </div>

    <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Compose</h4>
          </div>
          <form id="mti-email-form">
            <div class="modal-body">
              <div class="form-group">
                <input type="email" class="form-control" id="compose-to" placeholder="To" required />
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="compose-subject" placeholder="Subject" required />
              </div>

              <div class="form-group">
                <textarea class="form-control" id="compose-message" placeholder="Message" rows="10" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <div class="mti-form-sbt-btn btn btn-primary" >Submit</div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="reply-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Reply</h4>
          </div>
          <form class="mti-reply-form">
            <input type="hidden" id="reply-message-id" />

            <div class="modal-body">
              <div class="form-group">
                <input type="text" class="form-control" id="reply-to" placeholder="To" />
              </div>

              <div class="form-group">
                <input type="text" class="form-control disabled" id="reply-subject" placeholder="Sub"/>
              </div>

              <div class="form-group">
                <textarea class="form-control" id="reply-message" placeholder="Message" rows="10" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <div id="mti-email-reply-button" class="btn btn-primary">Send</div>
            </div>
          </form>
        </div>
      </div>
    </div>';