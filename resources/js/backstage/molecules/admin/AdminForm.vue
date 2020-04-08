<template>
    <div>
        <div class="form-row form-group">
            <label for="name" class="col-2 col-form-label">Name</label>

            <div class="col-10">
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-control"
                    :class="[errors['name'] ? 'is-invalid' : '']"
                    :value="name"
                    @input="$emit('update:name', $event.target.value)"
                    autofocus
                    required
                />

                <FormFeedback :errors="errors['name']" />
            </div>
        </div>

        <div class="form-row form-group">
            <label for="password" class="col-2 col-form-label">Password</label>

            <div class="col-10">
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="********"
                    class="form-control"
                    :class="[errors['password'] ? 'is-invalid' : '']"
                    :min="0"
                    :value="password"
                    @input="$emit('update:password', $event.target.value)"
                    autocomplete="off"
                />
                <FormFeedback :errors="errors['password']" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import FormFeedback from "../../../general/components/FormFeedback.vue";
import MoneyInput from "../../../general/components/MoneyInput.vue";

export default Vue.extend({
    name: "AdminForm",
    components: { MoneyInput, FormFeedback },

    props: {
        name: String,
        password: String,
        errors: Object,
    },
});
</script>
