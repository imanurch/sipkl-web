<td>
    <div class="flex justify-items-center justify-center space-x-2">
        <button @click="modalAction='isView';dataId={{ $data->toJson() }}" class="{{ $detail ?? '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path
                    d="M1.81497 9.53488C1.71283 9.37315 1.66176 9.29229 1.63317 9.16756C1.6117 9.07387 1.6117 8.92613 1.63317 8.83244C1.66176 8.70771 1.71283 8.62685 1.81497 8.46512C2.65902 7.12863 5.17143 3.75 9.00018 3.75C12.8289 3.75 15.3413 7.12863 16.1854 8.46512C16.2875 8.62685 16.3386 8.70771 16.3672 8.83244C16.3887 8.92613 16.3887 9.07387 16.3672 9.16756C16.3386 9.29229 16.2875 9.37315 16.1854 9.53488C15.3413 10.8714 12.8289 14.25 9.00018 14.25C5.17143 14.25 2.65903 10.8714 1.81497 9.53488Z"
                    stroke="#079455" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M9.00018 11.25C10.2428 11.25 11.2502 10.2426 11.2502 9C11.2502 7.75736 10.2428 6.75 9.00018 6.75C7.75754 6.75 6.75018 7.75736 6.75018 9C6.75018 10.2426 7.75754 11.25 9.00018 11.25Z"
                    stroke="#079455" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button @click="setFormAction('isEdit', {{ $data->id }});modalAction='isEdit';dataId={{ $data->toJson() }}"
            class="{{ $edit ?? '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path
                    d="M13.5 7.49998L10.5 4.49998M1.87494 16.125L4.41321 15.843C4.72333 15.8085 4.87839 15.7913 5.02332 15.7443C5.15191 15.7027 5.27427 15.6439 5.3871 15.5695C5.51428 15.4856 5.6246 15.3753 5.84523 15.1547L15.75 5.24998C16.5784 4.42156 16.5784 3.07841 15.75 2.24998C14.9215 1.42156 13.5784 1.42156 12.75 2.24998L2.84524 12.1547C2.6246 12.3753 2.51428 12.4856 2.43042 12.6128C2.35601 12.7256 2.2972 12.848 2.25557 12.9766C2.20866 13.1215 2.19143 13.2766 2.15697 13.5867L1.87494 16.125Z"
                    stroke="#F79008" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button
            @click="setFormAction('isDelete', {{ $data->id }});modalAction='isDelete';id={{ $data->id }};dataId={{ $data->toJson() }}"
            class="{{ $delete ?? '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path
                    d="M6.75 2.25H11.25M2.25 4.5H15.75M14.25 4.5L13.724 12.3895C13.6451 13.5732 13.6057 14.165 13.35 14.6138C13.1249 15.0088 12.7854 15.3265 12.3762 15.5248C11.9115 15.75 11.3183 15.75 10.132 15.75H7.86799C6.68168 15.75 6.08852 15.75 5.62375 15.5248C5.21457 15.3265 4.87507 15.0088 4.64999 14.6138C4.39433 14.165 4.35488 13.5732 4.27596 12.3895L3.75 4.5M7.5 7.875V11.625M10.5 7.875V11.625"
                    stroke="#D73328" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        {{-- <button @click="modal=true;dataId={{ $data->toJson() }}" class="btn btn-xs btn-default-fill {{ $btnInput ?? '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
            <path d="M7.49984 2.91669V11.0834M3.4165 7.00002H11.5832" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>Input Nilai</span>
    </button> --}}
    </div>
</td>
