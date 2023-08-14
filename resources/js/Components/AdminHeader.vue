<template>
  <nav class="navbar navbar-expand-lg shadow bg-body-tertiary p-2">
    <div class="container-fluid">
      <span class="navbar-brand">{{ $t('admin_panel') }}</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <Link class="nav-link" :class="[route().current('admin.dashboard') ? 'active' : '']"
              :href='route("admin.dashboard")'>
            <i class="bi bi-speedometer2 me-1"></i> {{ $t('dashboard') }}
            </Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link" :class="[route().current('admin.users.*') ? 'active' : '']"
              :href='route("admin.users.index")'>
            <i class="bi bi-people me-1"></i> {{ $t('users') }}
            </Link>
          </li>

          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"
              data-bs-target="#blog-dropdown">
              <i class="bi bi-postcard me-1"></i> {{ $t('blog') }}
            </a>
            <ul class="dropdown-menu text-sm " id="blog-dropdown">
              <li>
                <h6 class="dropdown-header">{{ $t('blog') }}</h6>
              </li>
              <li>
                <Link class="dropdown-item" :href="route('admin.posts.index')"><i class="bi bi-stickies me-2"></i>
                {{ $t('posts') }}
                </Link>
              </li>
              <li>
                <Link class="dropdown-item" :href="route('admin.categories.index')"><i class="bi bi-archive me-2"></i>
                {{ $t('categories') }}
                </Link>
              </li>
              <li>
                <Link class="dropdown-item" :href="route('admin.tags.index')"><i class="bi bi-tag me-2"></i>
                {{ $t('tags') }}
                </Link>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <Link :href="route('admin.pages.index')" class="nav-link"
              :class="[route().current('admin.pages.*') ? 'active' : '']"><i class="bi bi-file-text me-1"></i> {{
                $t('pages') }}</Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.file-manager.index')" class="nav-link"
              :class="[route().current('admin.file-manager.*') ? 'active' : '']"><i class="bi bi-folder me-1"></i> {{
                $t('file_manager') }}</Link>
          </li>
          <li class="nav-item">
            <Link :href="route('admin.settings.index')" class="nav-link"
              :class="[route().current('admin.settings.*') ? 'active' : '']"><i class="bi bi-gear-wide-connected me-1"></i> {{
                $t('settings') }}</Link>
          </li>
        </ul>
        <ul class='navbar-nav ms-auto align-items-center'>

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
              data-bs-target="#lang-dropdown"><i class="bi bi-globe"></i></a>

            <ul class="dropdown-menu" id="lang-dropdown">
              <a class="dropdown-item" href="/locale/en">English</a>
              <a class="dropdown-item" href="/locale/ar">العربية</a>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a href="#"
              class="nav-link link-body-emphasis d-flex align-items-center justify-content-center me-sm-3 text-decoration-none dropdown-toggle"
              data-bs-toggle="dropdown" data-bs-target="#user-dropdown" aria-expanded="false">
              <img v-if='user.photo' :src="user.photo" :alt="user.name" width="32" height="32"
                class="rounded-circle mx-1">
              <DefaultAvatar v-else class='me-2'><b>{{ user.name[0].toUpperCase() }}</b></DefaultAvatar>
              <span class="d-none d-md-inline">{{ user.name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small" id="user-dropdown">
              <li>
                <Link :href="route('admin.profile')" class="dropdown-item"><i class="bi bi-person me-2"></i> {{ $t('profile') }}</Link>
              </li>
              <li>
                <a href="#" class="dropdown-item">
                  <div class="form-switch">
                    <input class="form-check-input me-2" v-model="isDark" type="checkbox" role="switch" id="dark-model">
                    <label class="form-check-label" for="dark-model">{{ $t('dark_mode') }}</label>
                  </div>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <Link as="button" method="post" :href="route('admin.logout')" class="dropdown-item"><i class='bi bi-door-open me-2'></i> {{ $t('logout') }}
                </Link>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { computed, onMounted } from 'vue';
import { useDark, useToggle } from '@vueuse/core'
import DefaultAvatar from "./DefaultAvatar.vue";

const isDark = useDark({
  attribute: 'data-bs-theme',
  valueDark: 'dark',
  valueLight: 'light',
})

const toggleDark = useToggle(isDark)

const page = usePage();
const user = computed(() => page.props.auth.user);


onMounted(() => {
  // Toggle dropdown using data-bs-target
  const dropdownToggle = document.querySelectorAll('[data-bs-target]');

  dropdownToggle.forEach((toggle) => {
    toggle.addEventListener('click', (event) => {
      const targetSelector = toggle.getAttribute('data-bs-target');
      const target = document.querySelector(targetSelector);

      if (target) {
        target.classList.toggle('show')
      }

      event.preventDefault();
    });
  });

});

</script>
