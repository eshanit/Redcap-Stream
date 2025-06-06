<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref, Ref, onMounted } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import { AgGridVue } from 'ag-grid-vue3';
import {
    AllCommunityModule,
    ModuleRegistry,
    themeAlpine,
    themeBalham,
    themeMaterial,
    themeQuartz,
} from "ag-grid-community";



ModuleRegistry.registerModules([AllCommunityModule]);

const props = defineProps<{
    allResults: any[];
}>();


// Define column definitions
const columnDefs = ref();

columnDefs.value = [
    { headerName: 'Record ID', field: 'record', sortable: true, filter: true, width: 150, innerHeight:20 },
    { headerName: 'Health Facility', field: 'facility_demo', sortable: true, filter: true },
    { headerName: 'Age', field: 'age_demo', sortable: true, filter: true },
    { headerName: 'Gender', field: 'gender_demo', sortable: true, filter: true },
    { headerName: 'Enrollment Date', field: 'enroll_date_demo', sortable: true, filter: true },
      {
        headerName: "View",
        cellRenderer: (params: { data: { record: string; }; }) => {
            const button = document.createElement('button');
            button.className = "inline-flex items-center justify-center px-4 py-1 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-orange-500 border border-transparent rounded-md hover:bg-orange-500 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 cursor-pointer";
            button.innerText = "View";
            button.onclick = () => viewRecord(params.data.record);
            return button;
        },
    }
   
]

const viewRecord = (record: string) => {
    router.visit('/items/' + record + '/edit', { method: 'get' });
    // console.log(record);
    //emit('record', record);
};


// Transform statistics into row data
const rowData = ref([]);

const agGrid: Ref<any> = ref(null);
// Method to handle grid ready event
const onGridReady = (params: any) => {
    agGrid.value = params.api; // Store the grid API
    params.api.sizeColumnsToFit(); // Adjust column sizes
    updateFilteredRecordCount();

    // Listen for filter changes
    params.api.addEventListener('filterChanged', updateFilteredRecordCount);
};

const filteredRecordCount = ref(0);

const updateFilteredRecordCount = () => {
    console.log('agrid', agGrid.value)
    if (agGrid.value) {
        filteredRecordCount.value = agGrid.value.getDisplayedRowCount();
    }
};

const downloadCsv = () => {

    const gridApi = agGrid.value;
    console.log('AG Grid reference:', agGrid.value);
    if (gridApi) {
        gridApi.exportDataAsCsv();
    } else {
        console.error("AG Grid API is not available.");
    }
};



onMounted(() => {
});

const paginationPageSize = ref(100);
const paginationPageSizeSelector = ref<number[] | boolean>([10, 25, 50]);


</script>
<template>
    <div class="py-5">
        <Button @click="downloadCsv()">
            Download CSV
        </Button>
    </div>
    <!-- <pre>
        {{ props.allResults }}
    </pre> -->
    <div class="rounded-lg grid-container">
        <ag-grid-vue 
            :theme="themeQuartz"
            style="width: 100%; height: 1000px;" 
            @grid-ready="onGridReady" 
            :rowData="props.allResults"
            :columnDefs="columnDefs" 
            :defaultColDef="{ flex: 1, minWidth: 100 }" 
            :pagination="true"
            :paginationPageSize="paginationPageSize"
            :paginationPageSizeSelector="paginationPageSizeSelector"
            :headerHeight="40"
            :rowHeight="40" 
        ></ag-grid-vue>
    </div>
</template>
<style scoped>
/* Custom header styles */
:deep(.ag-theme-quartz) {
    --ag-header-background-color: #f0f0f0;  /* Light gray color */
    --ag-header-foreground-color: #000000;  /* Text color */
}

/* Adjust header height if needed (usually handled by the headerHeight prop) */
:deep(.ag-theme-quartz .ag-header) {
    height: 25px;
    min-height: 25px;
}

</style>