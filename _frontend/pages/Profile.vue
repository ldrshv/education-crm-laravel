<script setup>
    import { ref, onMounted, watch, watchEffect } from 'vue'
    import { useRoute, useRouter, onBeforeRouteUpdate } from 'vue-router'
    import axios from 'axios'
    import { mdiAccount, mdiTextBoxOutline, mdiFileDocument, mdiEye } from '@quasar/extras/mdi-v6'
    import BasePage from './BasePage.vue'
    import AppInput from '../components/AppInput.vue'
    import AppFilePond from '../components/AppFilePond.vue'
    import { showSuccess, showError, request } from '../functions.js'
    import { store } from '../store.js'

    const route = useRoute()
    const router = useRouter()

    const loading = ref(false)
    const formEl = ref(null)
    const form = ref({ description: '' })
    const formChanged = ref(false)
    const passwordForm = ref({})
    const passwordFormErrors = ref({})
    const files = ref({})
    const avatarPond = ref(null)
    const tab = ref('edit')
    const nextTab = ref(null)

    const save = function() {
        request({
            method: 'patch',
            path: `user`,
            data: form.value,
            //hasFiles: true,
            loading,
            successMessage: 'Профиль обновлён',
            success(response) {
                getProfile()
            },
            finally() {
                //formChanged.value = false
            },
        })
    }

    const changePassword = function() {
        request({
            method: 'post',
            path: `user/change-password`,
            data: passwordForm.value,
            loading,
            successMessage: 'Пароль изменён',
            success(response) {
                
            },
            finally() {
                
            },
        })
    }

    const uploadAvatar = function() {   
        request({
            method: 'post',
            path: `user/upload-avatar`,
            data: { avatar: avatarPond.value.getFiles()[0] },
            hasFiles: true,
            loading,
            successMessage: 'Аватар изменён',
            success(response) {
                
            },
            finally() {
                //formChanged.value = false
            },
        })
    }

    const getProfile = function() {
        request({
            path: `user`,
            loading,
            success(data) {
                form.value = data
                files.value.avatar = data.avatar ? [data.avatar] : []
                store.profile.avatar = data.avatar
            },
        })
    }

    const fields = [
        { field: 'name', label: 'Имя', validate: ['required'] },
        { field: 'phone', label: 'Телефон', validate: ['phone'] },
        // { field: 'fullname', label: 'Имя', validate: ['required'] },
        // { field: 'middlename', label: 'Отчество' },
        // { field: 'birthdate', label: 'Дата рождения', type: 'date' },
        // { field: 'country', label: 'Страна' },
        // { field: 'city', label: 'Город' },
    ]

    // const contactFields = [
    //     { field: 'phone', label: 'Телефон', validate: ['phone'] },
    //     { field: 'tg', label: 'Профиль в Телеграм' },
    //     { field: 'vk', label: 'Профиль в ВК' },
    // ]

    const passwordFields = [
        { field: 'old_password', type: 'password', label: 'Старый пароль' },
        { field: 'new_password', type: 'password', label: 'Новый пароль' },
        { field: 'new_password_confirmation', type: 'password', label: 'Подтвердите пароль', /*validate: ['confirm_password']*/ },
    ]

    getProfile()
</script>

<template>
    <base-page>
        <div class="main">
            <q-inner-loading :showing="loading" color="primary"/>

            <div class="main__sidebar">
                <q-form class="box" @submit="uploadAvatar">
                    <app-file-pond :files="files.avatar" ref="avatarPond" label="Загрузить аватар" :avatar="true"/>
                    <div class="d-flex q-mt-lg">
                        <q-btn type="submit" label="Сохранить" color="positive"/>
                    </div>
                </q-form>
            </div>

            <div class="">
                <q-form @submit="save" ref="formEl" class="box">
                    <h2 class="text-h6">Личные данные</h2>
                    <div class="row q-col-gutter-md">
                        <template v-for="i, n in fields">
                            <div class="col-12 col-sm-6">
                                <app-input v-model="form[i.field]" :opts="i" @update:model-value="formChanged = true"/>
                            </div>
                        </template>
                    </div>
                    <div class="d-flex q-mt-lg">
                        <q-btn type="submit" label="Сохранить" color="positive"/>
                    </div>
                </q-form>

                <q-form @submit="changePassword" class="box">
                    <h2 class="text-h6">Изменить пароль</h2>
                    <div class="q-gutter-md q-mt-none">
                        <template v-for="i in passwordFields">
                            <div class="">
                                <input type="text" name="password" hidden>
                                <app-input v-model="passwordForm[i.field]" :opts="i"
                                    @update:model-value="passwordFormErrors[i.field] = ''"
                                    :error="passwordFormErrors[i.field]"
                                    :form="passwordForm"
                                    autocomplete="new-password"
                                />
                            </div>
                        </template>
                    </div>
                    <div class="d-flex q-mt-lg">
                        <q-btn type="submit" label="Изменить пароль" color="positive"/>
                    </div>
                </q-form>

                <!-- <div class="box">
                    <h2 class="text-h6">Контактные данные</h2>
                    <div class="row q-col-gutter-lg">
                        <template v-for="i in contactFields">
                            <div class="col-12 col-md-6">
                                <app-input v-model="form[i.field]" :opts="i" @update:model-value="formChanged = true"/>
                            </div>
                        </template>
                    </div>
                </div> -->

                <!-- <div class="box">
                    <h2 class="text-h6">О себе</h2>
                    <q-editor v-model="form.description" @update:model-value="formChanged = true" min-height="5rem"/>
                </div> -->
            </div>
        </div>
    </base-page>
</template>