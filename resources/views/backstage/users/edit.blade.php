@extends('backstage.users.layout')

@section('HTML-main')
    @parent

    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="ui-title">Update user</h1>
            </div>
        </div>

        <hr/>

        <form @submit.prevent="updateUser">
            <user-form
                :name.sync="name"
                :email.sync="email"
                :balance.sync="balance"
                :password.sync="password"
                :errors="errors"
            ></user-form>

            <hr />

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-1">
                    <a class="btn btn-light btn-link" href="{{ route('users.index') }}">
                        Return
                    </a>
                </div>

                <div class="offset-2 offset-lg-1 col-1">
                    <action-button class="btn-dark">
                        Update
                    </action-button>
                </div>

                <div class="offset-5 col-1 offset-lg-8 col-lg-1">
                    <button
                        type="button"
                        class="btn btn-danger"
                        title="Delete"
                        @click='openDeleteModal(userId, name)'
                    >
                        Delete
                    </button>
                </div>
            </div>
        </form>
    </div>

    <modal-delete
        v-model="modalDeleteId"
        :text-description="modalDeleteDescription"
        @@destroy="deleteUser(modalDeleteId)"
    ></modal-delete>
@endsection
