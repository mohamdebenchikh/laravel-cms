<template>
    <AdminLayout>
        <template #head>
            <title>{{ $t('admin') }} | {{ $t('file_manager') }}</title>
        </template>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="flex-1">{{ $t('file_manager') }}</div>
                <div>
                    <AddNewFolder :folder='currentFolder' @created="reload"></AddNewFolder>
                </div>
            </div>
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <template v-for="(breadcrumb, index) in breadcrumbs" :key="index">
                            <li v-if="index === breadcrumbs.length - 1" class="breadcrumb-item active" aria-current="page">
                                {{ breadcrumb.name === 'public' ? 'Home' : breadcrumb.name }}
                            </li>
                            <li v-else class="breadcrumb-item">
                                <Link method="get" :href="route('admin.file-manager.index')"
                                    :data="{ folder: breadcrumb.path }">
                                {{ breadcrumb.name === 'public' ? 'Home' : breadcrumb.name }}
                                </Link>
                            </li>
                        </template>
                    </ol>
                </nav>


                <!-- Folders and files table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ $t('name') }}</th>
                            <th class="text-center">{{ $t('type') }}</th>
                            <th class="text-center">{{ $t('size') }}</th>
                            <th class="text-center">{{ $t('timestamp') }}</th>
                            <th class="text-center">{{ $t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for='item in combinedList' :key='item.name' @click="toggleRowSelection(item)"
                            :class="{ 'selected-row': selectedRows.includes(item) }">
                            <td>
                                <input type="checkbox" v-model="selectedItems" :value="item" />
                            </td>
                            <td v-if="item.type === 'folder'">
                                <Link method='get' :href="route('admin.file-manager.index')" :data="{ folder: item.path }">
                                <i class="bi bi-folder me-2"></i> {{ folderName(item.name) }}
                                </Link>
                            </td>
                            <td v-else>
                                <i class="bi bi-file-image me-2"></i> {{ fileName(item.name) }}
                            </td>
                            <td class="text-center">{{ item.type }}</td>

                            <td class="text-center">
                                <span v-if='item.type == "file"'>{{ formatSize(item.size) }}</span>
                                <span v-else><b>__</b></span>
                            </td>
                            <td class="text-center">{{ formatDate(item.timestamp) }}</td>
                            <td class="text-center">
                                <select v-if="item.type === 'file'">
                                    <option value="download">{{ $t('download') }}</option>
                                    <option value="delete">{{ $t('delete') }}</option>
                                </select>
                                <span v-else><b>__</b></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AddNewFolder from './AddNewFolder.vue';

const props = defineProps({
    folders: Array,
    files: Array,
    breadcrumbs: Array,
    currentFolder: String,
});

const selectedItems = ref([]);
const selectedRows = ref([]);


// Extract the folder name from the full path
const folderName = (folderPath) => {
    return folderPath.split('/').slice(-1)[0];
};

// Extract the file name from the full path
const fileName = (filePath) => {
    return filePath.split('/').slice(-1)[0];
};

// Format file size to a human-readable format
const formatSize = (bytes) => {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

// Format timestamp to a human-readable date
const formatDate = (timestamp) => {
    const date = new Date(timestamp * 1000); // Convert to milliseconds
    return date.toLocaleDateString();
};

// Combine folders and files with their details
const combinedList = ref([]);


onMounted(() => {
    combinedList.value = [
        ...props.folders.map(folder => ({ name: folderName(folder.name), type: 'folder', path: folder.name, timestamp: folder.timestamp, size: folder.size })),
        ...props.files.map(file => ({ name: fileName(file.path), type: 'file', timestamp: file.timestamp, size: file.size }))
    ];
});


const reload = () => {
    router.get(route('admin.file-manager.index'), {
        folder: props.currentFolder
    });
}


// Toggle row selection when clicked
const toggleRowSelection = (item) => {
    const index = selectedRows.value.indexOf(item);
    if (index === -1) {
        selectedRows.value.push(item);
    } else {
        selectedRows.value.splice(index, 1);
    }
}
</script>

<style scoped>
.selected-row {
    background-color: #f2f2f2;
    cursor: pointer;
}
</style>