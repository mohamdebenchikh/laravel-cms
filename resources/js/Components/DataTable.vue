<template>
    <div v-if='data.total > 0'>
        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex  align-items-center">
                <label for="per_page" class="me-2">{{$t('show')}}: </label>
                <select v-model="perPage" @change="reloadData" class="form-control form-control-sm" id="per_page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i>
                    </span>
                    <input v-model="search" @input="reloadData" type="text" class="form-control"
                        :placeholder="$t('search_for')" >
                </div>
            </div>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <template v-for="column in columns" :key="column">
                            <td class="text-center" v-if='column.sortable === false'>{{ $t(column.text) }}</td>
                            <td v-else>
                                <div class="d-flex justify-content-between">
                                    <span>{{ $t(column.text) }}</span>
                                    <Link
                                        :href="`${url}?sort_dir=${(sortDir === 'asc' ? 'desc' : 'asc')}&sort_by=${column.data}`">
                                    <i class="bi bi-sort-up" v-if="sortBy === column.data && sortDir === 'asc'"></i>
                                    <i class="bi bi-sort-down" v-else-if="sortBy === column.data && sortDir === 'desc'"></i>
                                    <i class="bi bi-sort-up" v-else></i>
                                    </Link>
                                </div>
                            </td>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <slot name='body' :items='data.data'></slot>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination">
                <li class="page-item" v-for="link in data.links" :class="[!link.url ? 'disabled' : '']">
                    <Link class="page-link" :class="[link.active ? 'active' : '']" :href="link.url ?? ''"
                        v-html='link.label'>
                    </Link>
                </li>

            </ul>
        </nav>
    </div>
    <div v-else class='p-4 text-center'>
        <p>{{ $t('no_records') }}</p>
    </div>
</template>
<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const search = ref('');
const page = usePage();
const sortBy = page.props.sortBy;
const sortDir = page.props.sortDir;
const perPage = ref(page.props.perPage ?? 10);

const props = defineProps({
    data: Object,
    columns: Array,
    url: '',
});

const reloadData = () => {
    router.get(props.url, { search: search.value, per_page: perPage.value },{
        preserveState:true,
        replace:true
    })
}

defineExpose({reload:reloadData})

</script>