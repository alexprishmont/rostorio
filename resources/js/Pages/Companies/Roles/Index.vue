<template>
  <Head title="Roles and permissions" />
  <OneColumnLayout>
    <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
      <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
        <div class="flex justify-end">
          <button
            class="transition inline-flex items-center px-4 py-2 border border-gray-500 bg-gray-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            type="button"
            @click="openAddRoleModal"
          >
            <PlusIcon class="w-4 h-4 mr-1" />
            Add a role
          </button>
        </div>
        <div class="w-full flex flex-col">
          <div
            v-for="role in roles"
            :key="role.id"
            class="mb-2"
          >
            <Disclosure v-slot="{ open }">
              <DisclosureButton
                class="flex justify-between w-full px-4 py-2 text-sm font-medium text-left text-sky-800 bg-sky-100 rounded-lg  hover:bg-sky-200 focus:outline-none"
              >
                <span>{{ role.name }}</span>
                <ChevronUpIcon
                  :class="open ? 'transform rotate-180' : ''"
                  class="w-5 h-5 text-sky-500"
                />
              </DisclosureButton>
              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-out"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <DisclosurePanel
                  class="p-5 text-sm text-gray-500"
                >
                  <div class="flex">
                    <div
                      v-for="permission in permissions"
                      :key="permission.id"
                      class="mr-5"
                    >
                      <SwitchGroup>
                        <div class="flex flex-col items-center">
                          <SwitchLabel passive>
                            {{ permission.name }}
                          </SwitchLabel>
                          <Switch
                            v-model="permissionStatuses[role.id][permission.id].status"
                            :class="permissionStatuses[role.id][permission.id].status ? 'bg-sky-500' : 'bg-sky-200'"
                            class="relative mt-1 inline flex items-center h-6 transition-colors rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                          >
                            <span
                              :class="permissionStatuses[role.id][permission.id].status ? 'translate-x-6' : 'translate-x-1'"
                              class="inline-block w-4 h-4 transition-transform transform bg-white rounded-full"
                            />
                          </Switch>
                        </div>
                      </SwitchGroup>
                    </div>
                  </div>

                  <div class="mt-4 flex flex-inline justify-between">
                    <button
                      class="transition inline-flex items-center px-4 py-2 border border-cyan-500 bg-cyan-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                      type="button"
                      @click="togglePermissions(role)"
                    >
                      Save
                    </button>

                    <button
                      class="transition inline-flex items-center px-4 py-2 border border-red-500 bg-red-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                      type="button"
                      @click="confirmRemovalWindow.open(role)"
                    >
                      Remove role
                    </button>
                  </div>
                </DisclosurePanel>
              </transition>
            </Disclosure>
          </div>
        </div>
      </div>
    </section>
  </OneColumnLayout>

  <ConfirmModal
    :show="isRemovalWindowShow"
    @close="confirmRemovalWindow.close"
    @deny="confirmRemovalWindow.close"
    @accept="confirmRemovalWindow.accept"
  >
    <template #title>
      Are you sure you want to delete role?
    </template>

    <template #message>
      Do you want to delete <b>{{ confirmRemovalWindow.currentRole.name }}</b> role?
    </template>
  </ConfirmModal>

  <FillableModal
    :show="isRoleAddModalVisible"
    @close="closeRoleAddModal"
  >
    <template #title>
      Add a new role
    </template>

    <template #body>
      <form
        class="min-h-[12rem]"
        @submit.prevent="submitAddRoleForm"
      >
        <div class="mt-2">
          <ErrorAlert />
        </div>

        <div class="mt-4">
          <Input
            v-model="roleAddForm.name"
            id-name="name"
            type="text"
          >
            Name
          </Input>
        </div>

        <div class="mt-2">
          <Multiselect
            v-model="roleAddForm.permissions"
            :classes="{
              container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 bg-white text-base leading-snug outline-none rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600',
              tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
              dropdown: 'max-h-20 absolute -left-px -right-px bottom-0 transform translate-y-full border border-gray-300 -mt-px overflow-y-scroll z-50 bg-white flex flex-col rounded-b',
              dropdownTop: '-translate-y-full top-px bottom-auto flex-col-reverse rounded-b-none rounded-t',
              dropdownHidden: 'hidden',
            }"
            :close-on-select="false"
            :create-tag="false"
            :options="permissions"
            :searchable="true"
            label="name"
            mode="tags"
            track-by="name"
            value-prop="id"
          >
            <template #tag="{ option, handleTagRemove, disabled }">
              <div
                class="inline-flex items-center px-3 py-0.5 rounded-full m-1 text-sm font-medium bg-cyan-100 text-cyan-800"
              >
                {{ option.name }}
                <span
                  v-if="!disabled"
                  class="multiselect-tag-remove cursor-pointer"
                  @mousedown.prevent="handleTagRemove(option, $event)"
                >
                  <span class="multiselect-tag-remove-icon" />
                </span>
              </div>
            </template>
          </Multiselect>
        </div>
        <div class="mt-4">
          <button
            class="transition inline-flex items-center px-4 py-2 border border-cyan-500 bg-cyan-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
            type="submit"
          >
            Add
          </button>
        </div>
      </form>
    </template>
  </FillableModal>
</template>

<script setup>
import OneColumnLayout from '@/Layouts/Authenticated/OneColumnLayout';
import {Disclosure, DisclosureButton, DisclosurePanel, Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue';
import {ChevronUpIcon, PlusIcon} from '@heroicons/vue/solid';
import {ref} from 'vue';
import FillableModal from '@/Components/Modals/FillableModal';
import ConfirmModal from '@/Components/Modals/ConfirmModal';
import {useForm} from '@inertiajs/inertia-vue3';
import Input from '@/Components/Inputs/Input';
import ErrorAlert from '@/Components/ErrorAlert';
import Multiselect from '@vueform/multiselect';
import {Inertia} from '@inertiajs/inertia';

const props = defineProps({
    roles: Array,
    permissions: Array,
    permissionStatuses: Object,
});

const isRoleAddModalVisible = ref(false);
const roleAddForm = useForm({
    name: '',
    permissions: null,
});

const openAddRoleModal = () => {
    isRoleAddModalVisible.value = true;
};

const closeRoleAddModal = () => {
    isRoleAddModalVisible.value = false;
};

const submitAddRoleForm = () => {
    roleAddForm.post('/company/roles');
};

const togglePermissions = (role) => {
    Inertia.put(`/company/roles/${role.id}`,
        {
            permissions: props.permissionStatuses[role.id]
        },
        {
            onSuccess: () => {
                isRoleAddModalVisible.value = false;
            }
        }
    );
};

const isRemovalWindowShow = ref(false);

const confirmRemovalWindow = {
    currentRole: null,
    open: (role) => {
        isRemovalWindowShow.value = true;
        confirmRemovalWindow.currentRole = role;
    },
    close: () => {
        isRemovalWindowShow.value = false;
    },
    accept: () => {
        Inertia.delete(`/company/roles/${confirmRemovalWindow.currentRole.id}`);
        isRemovalWindowShow.value = false;
    },
};
</script>
