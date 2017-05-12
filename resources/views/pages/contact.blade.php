@extends('main')

@section('title', '| Contact Us')
@section('contactActive', 'active')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Contact Us</h1>
            <hr>
            <form>
                <div class="form-group">
                    <label name="subject"> Subject: </label>
                    <input id="subject" name="subject" class="form-control" placeholder="Subject">
                </div>
                <div class="form-group">
                    <label name="message"> Message: </label>
                    <textarea id="message" name="message" class="form-control" placeholder="Type your message here..."></textarea>
                </div>

                <input type="submit" value="Send Message" class="btn btn-success">
            </form>
        </div>
    </div>
@endsection