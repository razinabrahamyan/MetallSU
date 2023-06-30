<template>
    <div>
        <div :class="['main_edit_block w-100',deleted ? 'deleted' : '',sub_items.length || (children && children.length) ? 'has_children_block' : '',
                                                has_export_errors? 'not_choosed' : '',
                                                !children_open && !editable ? 'hidden' : '',
                                                ]"
             :title="!group_id && !sub_items.length?'Данный предмет(услуга) не указан ни в одной из групп выгрузки':''"  >
            <div :class="['d-flex justify-content-between position-relative',!sub_items.length?'flex-wrap' : 'mb-2']">
                <div :class="['d-flex align-items-start position_edit_card ',mainPositionClass()]"
                     :title="has_export_errors?'В данной категории есть предмет не указанный ни в одной из групп выгрузки' : ''">
                    <div :class="['d-flex flex-nowrap align-items-center pt-4 position-relative', !(sub_items.length || (children && children.length))? 'flex-sm-row' : '']">


                        <div class="d-flex align-items-center ">
                            <a :href="sub_items.length ? '/category/' + id : '/product-item/' + id">
                                <i class="bx bx-bar-chart-square font-22 to_statistic_icon"></i>
                            </a>

                            <div class="form-group p-1 inputs">
                                <input  autocomplete="off"
                                        :disabled="!editable || deleted"
                                        required
                                        v-model="title"
                                        ref="input"
                                        type="text"
                                        :class="['form-control main_value',{'error':!title}]" >
                            </div>
                        </div>



                        <div class="d-flex flex-nowrap item_edit_buttons justify-content-center ">
                            <button class="btn btn-light should_dis edit_button"
                                    @click="editItem()"
                                    :disabled="deleted"
                                    type="button">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button @click="addChild()"
                                    :disabled="!editable || deleted"
                                    class="btn btn-light should_dis text-warning add_button"
                                    type="button">
                                <i class="bx bx-plus"></i>
                            </button>
                            <button @click="deleteItem()"
                                    :disabled="!editable || deleted"
                                    class="btn btn-light should_dis text-danger delete_button"
                                    type="button">
                                <i class="bx bx-trash"></i>
                            </button>
                            <button @click="restoreItem()"
                                    class="btn btn-light restore_button essential"
                                    type="button">
                                <i class="bx bx-undo"></i>
                            </button>

                            <button v-if="sub_items.length || (children && children.length)" :disabled="deleted"
                                    class="btn btn-light should_dis open_children_button"
                                    @click="openChildren()"
                                    type="button">
                                <i class="bx bx-exclude open_children_icon"></i>
                                <i class="bx bx-unite close_children_icon"></i>
                            </button>
                        </div>
                        <div class="fuck_this_shit deleted">
                            удалено
                        </div>
                        <div v-show="!deleted" class="top_signs">
                            <div v-if="!id" class="new_sign">новый</div>
                            <div class="export_group_not_choosed_sign">укажите гр. экспорта</div>
                        </div>
                    </div>

                    <div v-if="is_self_edited" class="d-flex flex-wrap item_edit_buttons align-items-start p-1">
                        <div class="d-flex flex-wrap">
                            <div v-for="(responsibleGroup,index) in responsibles"
                                 class="form-group ps-2 position-relative pt-4 default_responsible_select_handler">
                                <div class="fuck_this_shit" v-text="responsibleGroup.name"></div>
                                <select :disabled="!editable || deleted"
                                        class="default_responsible_select form-select  select_2_select"
                                        :value="defaultResponsibles[responsibleGroup.id] ? defaultResponsibles[responsibleGroup.id].value: 'no_responsible'"
                                        @change="responsibleChanged($event,responsibleGroup.id)"
                                >
                                    <option selected value="no_responsible" > Не Указывать</option>

                                    <option v-for="responsible in responsibleGroup.responsibles"
                                            :value="responsible.id" v-text="responsible.name"></option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex flex-nowrap">
                            <div class="form-group ps-2 pt-2 position-relative fuck_the_police">

                                <div class="form-check form-switch">
                                    <input :disabled="!editable || deleted"
                                           v-model="required_count"
                                           class="form-check-input"
                                           type="checkbox"
                                    >
                                    <label class="form-check-label">Обязательное колиество</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input :disabled="!editable || deleted"
                                           :checked="required_responsible"
                                           class="form-check-input"
                                           type="checkbox">
                                    <label class="form-check-label" >Обязательный Ответственный</label>
                                </div>
                            </div>
                            <div class="settings_absolute">
                                <div class="custom_dropdown position_dropdown col-md-auto col-sm-auto">
                                    <button :disabled="!editable || deleted"
                                            type="button"
                                            class="btn btn-light button_no_nothing custom_dropdown_toggler m-1">
                                        <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.4432 17.4042C17.9744 16.771 18.7023 15.0166 18.0692 13.4855C17.4361 11.9543 15.6816 11.2264
                            14.1505 11.8595C12.6194 12.4926 11.8914 14.2471 12.5245 15.7782C13.1577 17.3093 14.9121 18.0373 16.4432 17.4042Z"
                                                  stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M23.2816 14.5765C23.2738 14.9061 23.365 15.2304 23.5433 15.5077C23.7216 15.785 23.9789
                            16.0025 24.282 16.1322L24.3604 16.1648C24.6032 16.2654 24.8238 16.4128 25.0096
                            16.5987C25.1954 16.7847 25.3427 17.0054 25.4431 17.2482C25.5436 17.4911 25.5951
                            17.7514 25.5949 18.0143C25.5947 18.2771 25.5426 18.5373 25.4418 18.78C25.3412
                            19.0228 25.1937 19.2434 25.0078 19.4292C24.8219 19.615 24.6012 19.7623 24.3583
                            19.8628C24.1154 19.9632 23.8551 20.0148 23.5923 20.0145C23.3295 20.0143 23.0692
                            19.9623 22.8265 19.8614L22.7482 19.8289C22.4423 19.7059 22.1066 19.6774 21.7843
                            19.747C21.4621 19.8166 21.168 19.9811 20.9402 20.2194C20.7153 20.4496 20.5626
                            20.7405 20.5009 21.0563C20.4393 21.3721 20.4713 21.6991 20.5931 21.9969L20.658
                            22.154C20.8607 22.6442 20.8604 23.1948 20.6571 23.6848C20.4538 24.1747 20.0642
                            24.5638 19.5741 24.7665C19.0839 24.9692 18.5333 24.9688 18.0433 24.7656C17.5534
                            24.5623 17.1643 24.1727 16.9616 23.6825L16.9272 23.5993C16.7936 23.2964 16.5719
                            23.0407 16.291 22.8654C16.0101 22.6902 15.683 22.6035 15.3521 22.6166C15.0226
                            22.6089 14.6982 22.7 14.4209 22.8783C14.1436 23.0566 13.9261 23.3139 13.7964
                            23.617L13.7638 23.6954C13.6633 23.9382 13.5158 24.1588 13.3299 24.3446C13.144
                            24.5304 12.9233 24.6777 12.6804 24.7782C12.4375 24.8786 12.1772 24.9302 11.9144
                            24.9299C11.6515 24.9297 11.3913 24.8777 11.1486 24.7768C10.9058 24.6762 10.6852
                            24.5287 10.4994 24.3428C10.3136 24.1569 10.1663 23.9362 10.0658 23.6933C9.96541
                            23.4504 9.91384 23.1902 9.91407 22.9273C9.91431 22.6645 9.96634 22.4043 10.0672
                            22.1616L10.0997 22.0832C10.2227 21.7773 10.2512 21.4416 10.1816 21.1193C10.112 20.7971
                            9.94749 20.5031 9.70922 20.2752C9.47906 20.0503 9.18816 19.8977 8.87234 19.836C8.55651
                            19.7743 8.22955 19.8063 7.93169 19.9281L7.77459 19.9931C7.28441 20.1958 6.73379 20.1954
                            6.24386 19.9921C5.75393 19.7888 5.36481 19.3993 5.16213 18.9091C4.95944 18.4189 4.95977
                            17.8683 5.16306 17.3784C5.36634 16.8884 5.75593 16.4993 6.24611 16.2966L6.32928 16.2622C6.63219
                            16.1286 6.8879 15.9069 7.06316 15.626C7.23842 15.3451 7.32512 15.018 7.312 14.6872C7.31976
                            14.3576 7.2286 14.0332 7.05029 13.7559C6.87197 13.4786 6.61468 13.2611 6.31158 13.1314L6.2332
                            13.0989C5.99039 12.9983 5.76978 12.8508 5.58399 12.6649C5.3982 12.479 5.25088 12.2583 5.15044
                            12.0154C5.05001 11.7725 4.99844 11.5122 4.99867 11.2494C4.9989 10.9866 5.05094 10.7263 5.1518
                            10.4836C5.2524 10.2408 5.39986 10.0202 5.58577 9.83442C5.77169 9.64864 5.99239 9.50131 6.23528
                            9.40088C6.47816 9.30045 6.73846 9.24887 7.00129 9.24911C7.26412 9.24934 7.52433 9.30138 7.76704
                            9.40224L7.84541 9.43476C8.1513 9.55774 8.48702 9.58627 8.80927 9.51667C9.13152 9.44707 9.42554
                            9.28252 9.6534 9.04425L9.72733 9.01369C9.95221 8.78352 10.1049 8.49262 10.1666 8.1768C10.2282 7.86098
                            10.1962 7.53401 10.0744 7.23615L10.0095 7.07906C9.80679 6.58888 9.80712 6.03825 10.0104 5.54832C10.2137
                            5.05839 10.6033 4.66928 11.0935 4.46659C11.5836 4.2639 12.1343 4.26423 12.6242 4.46752C13.1141 4.67081
                            13.5032 5.06039 13.7059 5.55057L13.7403 5.63374C13.8645 5.93062 14.0727 6.18471 14.3394 6.36475C14.6061
                            6.54479 14.9197 6.64291 15.2414 6.64703C15.571 6.65479 15.8954 6.56363 16.1727 6.38532C16.45 6.20701
                            16.6675 5.94971 16.7972 5.64661L16.8297 5.56824C16.9303 5.32542 17.0778 5.10481 17.2637 4.91902C17.4496
                            4.73323 17.6703 4.58591 17.9132 4.48548C18.1561 4.38504 18.4164 4.33347 18.6792 4.3337C18.9421 4.33394
                            19.2023 4.38597 19.445 4.48684C19.6878 4.58743 19.9084 4.7349 20.0942 4.92081C20.28 5.10672 20.4273 5.32743
                            20.5277 5.57031C20.6282 5.8132 20.6797 6.0735 20.6795 6.33633C20.6793 6.59916 20.6272 6.85937 20.5264 7.10207L20.4939
                            7.18045C20.3709 7.48633 20.3423 7.82205 20.4119 8.1443C20.4815 8.46656 20.6461 8.76057 20.8844 8.98843L20.9149
                            9.06236C21.1451 9.28724 21.436 9.43992 21.7518 9.50159C22.0676 9.56327 22.3946 9.53124 22.6925 9.40947L22.8496
                            9.34451C23.3397 9.14182 23.8904 9.14215 24.3803 9.34544C24.8702 9.54873 25.2593 9.93831 25.462 10.4285C25.6647
                            10.9187 25.6644 11.4693 25.4611 11.9592C25.2578 12.4492 24.8682 12.8383 24.378 13.041L24.2949 13.0753C23.998
                            13.1995 23.7439 13.4077 23.5639 13.6745C23.3838 13.9412 23.2857 14.2547 23.2816 14.5765V14.5765Z"
                                                  stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div class="custom_dropdown_menu px-2 " >
                                        <div class="form-group">
                                            <label>Группа</label>
                                            <select  :disabled="!editable || deleted"
                                                     v-model="position_id"
                                                     class="default_position_select form-select should_dis select_2_select"
                                            >
                                                <option v-for="select_position in positions"
                                                        :value="select_position.id"
                                                        v-text="select_position.title"
                                                >
                                                </option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Группа Экспорта</label>

                                            <select :disabled="!editable || deleted"
                                                    v-model="group_id"
                                                    class="default_position_select form-select should_dis select_2_select export_select"
                                            >
                                                <option value="no_group">Не Указывать</option>
                                                <option v-for="group in export_groups"
                                                        :value="group.id"
                                                        v-text="group.title">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>

                <div class="settings_absolute">
                    <div v-if="!is_self_edited" class="custom_dropdown position_dropdown col-md-auto col-sm-auto">
                        <button :disabled="!editable || deleted"
                                type="button"
                                class="btn btn-light button_no_nothing custom_dropdown_toggler m-1">
                            <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.4432 17.4042C17.9744 16.771 18.7023 15.0166 18.0692 13.4855C17.4361 11.9543 15.6816 11.2264
                            14.1505 11.8595C12.6194 12.4926 11.8914 14.2471 12.5245 15.7782C13.1577 17.3093 14.9121 18.0373 16.4432 17.4042Z"
                                      stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M23.2816 14.5765C23.2738 14.9061 23.365 15.2304 23.5433 15.5077C23.7216 15.785 23.9789
                            16.0025 24.282 16.1322L24.3604 16.1648C24.6032 16.2654 24.8238 16.4128 25.0096
                            16.5987C25.1954 16.7847 25.3427 17.0054 25.4431 17.2482C25.5436 17.4911 25.5951
                            17.7514 25.5949 18.0143C25.5947 18.2771 25.5426 18.5373 25.4418 18.78C25.3412
                            19.0228 25.1937 19.2434 25.0078 19.4292C24.8219 19.615 24.6012 19.7623 24.3583
                            19.8628C24.1154 19.9632 23.8551 20.0148 23.5923 20.0145C23.3295 20.0143 23.0692
                            19.9623 22.8265 19.8614L22.7482 19.8289C22.4423 19.7059 22.1066 19.6774 21.7843
                            19.747C21.4621 19.8166 21.168 19.9811 20.9402 20.2194C20.7153 20.4496 20.5626
                            20.7405 20.5009 21.0563C20.4393 21.3721 20.4713 21.6991 20.5931 21.9969L20.658
                            22.154C20.8607 22.6442 20.8604 23.1948 20.6571 23.6848C20.4538 24.1747 20.0642
                            24.5638 19.5741 24.7665C19.0839 24.9692 18.5333 24.9688 18.0433 24.7656C17.5534
                            24.5623 17.1643 24.1727 16.9616 23.6825L16.9272 23.5993C16.7936 23.2964 16.5719
                            23.0407 16.291 22.8654C16.0101 22.6902 15.683 22.6035 15.3521 22.6166C15.0226
                            22.6089 14.6982 22.7 14.4209 22.8783C14.1436 23.0566 13.9261 23.3139 13.7964
                            23.617L13.7638 23.6954C13.6633 23.9382 13.5158 24.1588 13.3299 24.3446C13.144
                            24.5304 12.9233 24.6777 12.6804 24.7782C12.4375 24.8786 12.1772 24.9302 11.9144
                            24.9299C11.6515 24.9297 11.3913 24.8777 11.1486 24.7768C10.9058 24.6762 10.6852
                            24.5287 10.4994 24.3428C10.3136 24.1569 10.1663 23.9362 10.0658 23.6933C9.96541
                            23.4504 9.91384 23.1902 9.91407 22.9273C9.91431 22.6645 9.96634 22.4043 10.0672
                            22.1616L10.0997 22.0832C10.2227 21.7773 10.2512 21.4416 10.1816 21.1193C10.112 20.7971
                            9.94749 20.5031 9.70922 20.2752C9.47906 20.0503 9.18816 19.8977 8.87234 19.836C8.55651
                            19.7743 8.22955 19.8063 7.93169 19.9281L7.77459 19.9931C7.28441 20.1958 6.73379 20.1954
                            6.24386 19.9921C5.75393 19.7888 5.36481 19.3993 5.16213 18.9091C4.95944 18.4189 4.95977
                            17.8683 5.16306 17.3784C5.36634 16.8884 5.75593 16.4993 6.24611 16.2966L6.32928 16.2622C6.63219
                            16.1286 6.8879 15.9069 7.06316 15.626C7.23842 15.3451 7.32512 15.018 7.312 14.6872C7.31976
                            14.3576 7.2286 14.0332 7.05029 13.7559C6.87197 13.4786 6.61468 13.2611 6.31158 13.1314L6.2332
                            13.0989C5.99039 12.9983 5.76978 12.8508 5.58399 12.6649C5.3982 12.479 5.25088 12.2583 5.15044
                            12.0154C5.05001 11.7725 4.99844 11.5122 4.99867 11.2494C4.9989 10.9866 5.05094 10.7263 5.1518
                            10.4836C5.2524 10.2408 5.39986 10.0202 5.58577 9.83442C5.77169 9.64864 5.99239 9.50131 6.23528
                            9.40088C6.47816 9.30045 6.73846 9.24887 7.00129 9.24911C7.26412 9.24934 7.52433 9.30138 7.76704
                            9.40224L7.84541 9.43476C8.1513 9.55774 8.48702 9.58627 8.80927 9.51667C9.13152 9.44707 9.42554
                            9.28252 9.6534 9.04425L9.72733 9.01369C9.95221 8.78352 10.1049 8.49262 10.1666 8.1768C10.2282 7.86098
                            10.1962 7.53401 10.0744 7.23615L10.0095 7.07906C9.80679 6.58888 9.80712 6.03825 10.0104 5.54832C10.2137
                            5.05839 10.6033 4.66928 11.0935 4.46659C11.5836 4.2639 12.1343 4.26423 12.6242 4.46752C13.1141 4.67081
                            13.5032 5.06039 13.7059 5.55057L13.7403 5.63374C13.8645 5.93062 14.0727 6.18471 14.3394 6.36475C14.6061
                            6.54479 14.9197 6.64291 15.2414 6.64703C15.571 6.65479 15.8954 6.56363 16.1727 6.38532C16.45 6.20701
                            16.6675 5.94971 16.7972 5.64661L16.8297 5.56824C16.9303 5.32542 17.0778 5.10481 17.2637 4.91902C17.4496
                            4.73323 17.6703 4.58591 17.9132 4.48548C18.1561 4.38504 18.4164 4.33347 18.6792 4.3337C18.9421 4.33394
                            19.2023 4.38597 19.445 4.48684C19.6878 4.58743 19.9084 4.7349 20.0942 4.92081C20.28 5.10672 20.4273 5.32743
                            20.5277 5.57031C20.6282 5.8132 20.6797 6.0735 20.6795 6.33633C20.6793 6.59916 20.6272 6.85937 20.5264 7.10207L20.4939
                            7.18045C20.3709 7.48633 20.3423 7.82205 20.4119 8.1443C20.4815 8.46656 20.6461 8.76057 20.8844 8.98843L20.9149
                            9.06236C21.1451 9.28724 21.436 9.43992 21.7518 9.50159C22.0676 9.56327 22.3946 9.53124 22.6925 9.40947L22.8496
                            9.34451C23.3397 9.14182 23.8904 9.14215 24.3803 9.34544C24.8702 9.54873 25.2593 9.93831 25.462 10.4285C25.6647
                            10.9187 25.6644 11.4693 25.4611 11.9592C25.2578 12.4492 24.8682 12.8383 24.378 13.041L24.2949 13.0753C23.998
                            13.1995 23.7439 13.4077 23.5639 13.6745C23.3838 13.9412 23.2857 14.2547 23.2816 14.5765V14.5765Z"
                                      stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="custom_dropdown_menu px-2 " >
                            <div class="form-group">
                                <label>Группа</label>
                                <select :disabled="!editable || deleted"
                                        required
                                        v-model="position_id"
                                        class="default_position_select form-select should_dis select_2_select">
                                    <option v-for="select_position in positions" :value="select_position.id" v-text="select_position.title"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="children" v-show="children_open">
                <div class="d-flex flex-column-reverse">
                    <div v-if="children && children.length"
                         v-for="(child,child_index) in children"
                    >
                        <sub-item-component

                            :bind_parent="item"
                            :bind_parent_deleted="deleted"
                            :bind_item="child"
                            :bind_responsibles="responsibles"
                            :bind_export_groups="export_groups"
                            :bind_group_index="group_index"
                            :bind_loop_index="loop_index"
                            :bind_sub_loop_index="child_index"
                            ref="child"
                            @subitem-changed="subItemUpdated"
                        >
                        </sub-item-component>
                    </div>
                </div>


                <div   v-if="sub_items && sub_items.length"
                       v-for="(sub_item,sub_loop_index) in sub_items">
                    <sub-item-component
                        :key="sub_item.id"
                        :bind_parent="item"
                        :bind_parent_deleted="deleted"
                        :bind_item="sub_item"
                        :bind_responsibles="responsibles"
                        :bind_export_groups="export_groups"
                        :bind_group_index="group_index"
                        :bind_loop_index="loop_index"
                        :bind_sub_loop_index="sub_loop_index"
                        :bind_has_match="sub_item.has_match"
                        ref="sub_item"
                        @subitem-changed="subItemUpdated"
                    >
                    </sub-item-component>
                </div>



            </div>
        </div>
    </div>
</template>

<script>
import SubItemComponent from "./SubItemComponent";
export default {
    name: "ItemComponent",
    components:{
        SubItemComponent
    },
    props: [
        'bind_item',
        'bind_responsibles',
        'bind_export_groups',
        'bind_positions',
        'bind_group_index',
        'bind_loop_index'
    ],
    data: function() {
        return {
            id:this.bind_item.id,
            item:this.bind_item,
            type:'item',
            title:this.bind_item.title,
            editable: this.bind_item.editable,
            position_id:this.bind_item.position_id,
            deleted:this.bind_item.deleted ?? false,
            group_id:this.bind_item.group_id ?? 'no_group',
            required_count:this.bind_item.required_count,
            required_responsible:this.bind_item.required_responsible,
            children_open:this.bind_item.children_open,
            responsibles:this.bind_responsibles,
            export_groups:this.bind_export_groups,
            sub_items:this.bind_item.sub_items,
            children:[],
            positions:this.bind_positions,
            group_index:this.bind_group_index,
            loop_index:this.bind_loop_index,
            defaultResponsibles:this.bind_item.groupedResponsibles,
            is_self_edited:false,
            current_position:this.bind_item.position_id,
            has_export_errors:false

        };
    },
    watch:{
        bind_item:{
            handler:function (value){
                this.sub_items = value.sub_items
                this.children = value.children ?? []
                this.has_export_errors = this.checkExportGroupErrors();
                this.editable =  this.bind_item.editable ?? false;
            },
            deep:true,
            immediate:true
        },
    },
    mounted() {
        this.has_export_errors = this.checkExportGroupErrors();
        if(this.editable){
            this.$refs.input.focus()
        }
        this.is_self_edited = this.checkIfEditable()
        this.$watch(
            (vm) => [
                vm.id,
                vm.title,
                vm.type,
                vm.deleted,
                vm.group_id,
                vm.has_match,
                vm.required_responsible,
                vm.required_count,
                vm.position_id
            ],
            () => {
                if(this.editable){
                    this.sendUpdatedItemInfo(this.returnValue(),{position_id:this.current_position,item_index:this.loop_index})
                }
            },
            {
                immediate: true,
                deep: true,
            }
        );
    },
    methods:{
        consoleInfo(){
            console.log(this.returnValue())
        },
        returnValue(){
            return {
                id:this.id,
                title:this.title,
                deleted:this.deleted,
                group_id:this.group_id,
                editable:this.editable,
                has_match:this.has_match,
                required_count:this.required_count ? 1 : null,
                required_responsible:this.required_responsible? 1 : null,
                defaultResponsibles:Object.values(this.defaultResponsibles),
                type:this.type,
                parent_id:null,
                children:this.children,
                position_id:this.position_id,
            }
        },
        checkSubitemTitlesUniqueness(){
            let titles = [];
            Object.values(this.children)
                .map((value,child_index) => {
                    if(!value.deleted && value.title){
                        titles.push({
                            ref:'child',
                            index:child_index,
                            title:value.title,
                            has_match:false,
                            force:false
                        })
                    } else{
                        titles.push({
                            ref:'child',
                            index:child_index,
                            title:value.title,
                            has_match:false,
                            force:true
                        })
                    }

                });
            Object.values(this.sub_items)
                .map((value,subitem_index) =>{
                    if(!value.deleted && value.title){
                        titles.push({
                            ref:'sub_item',
                            index:subitem_index,
                            title:value.title,
                            has_match:false,
                            force:false
                        })
                    } else{
                        titles.push({
                            ref:'sub_item',
                            index:subitem_index,
                            title:value.title,
                            has_match:false,
                            force:true
                        })
                    }

                } );
            for(let i = 0; i < titles.length; i++){
                if(titles[i].has_match || titles[i].force){continue;}
                for(let j = i+1; j < titles.length; j++){

                    if($.trim(titles[i].title.toUpperCase()) === $.trim(titles[j].title.toUpperCase()) && !titles[j].force){
                        titles[i].has_match = true;
                        titles[j].has_match = true;
                    }
                }
            }
            Object.values(titles).map(value => {
                this.$refs[value.ref][value.index].setMatch(value.has_match);

            })

        },
        subItemUpdated(data){
            if(data.id){
                this.sub_items[data.index] = data;
            }
            else{
                this.children[data.index] = data
            }

            this.is_self_edited = this.checkIfEditable()
            this.checkSubitemTitlesUniqueness();
            this.sendUpdatedItemInfo(data,{position_id:this.current_position,item_index:this.loop_index})
        },
        sendUpdatedItemInfo(data,index){
            this.$emit('item-updated',data,index)
        },
        checkIfEditable(){
            let editable = true;
            if(this.sub_items && this.sub_items.length){
                this.sub_items.some((value => {
                    if(!value.deleted){
                        editable = false;
                        return true
                    }
                    return false;
                }))
            }
            if(editable &&  this.children && this.children.length){
                this.children.some((value => {
                    if(!value.deleted){
                        editable = false;
                        return true
                    }
                    return false;
                }))
            }
            return editable;

        },
        responsibleChanged(event,responsible_group_id){

            let value = event.target.value ;

            if(!this.defaultResponsibles[responsible_group_id]){
                this.defaultResponsibles[responsible_group_id] = {old_value:'no_responsible',value:value}
            }else{
                this.defaultResponsibles[responsible_group_id].value = value;
            }
            this.sendUpdatedItemInfo(this.returnValue(),{position_id:this.position_id, item_index:this.loop_index})

        },
        editItem(){
            this.editable = true;
            this.openChildren(true)
        },
        deleteItem(){
            this.deleted = true;
            this.openChildren(true)
            this.$forceUpdate()
        },
        restoreItem(){
            this.deleted = false;
        },
        addChild(){

            let child = {
                id:null,
                groupedResponsibles:[],
                group_id:null,
                parent_id:this.id,
                required_count:false,
                required_responsible:false,
                title: '',
                editable:true,
            }
            this.children.push(child);
            this.openChildren(true)
        },
        openChildren(force_value = null){
            this.children_open = force_value !== null ? force_value : !this.children_open;

        },
        subItemExportGroupChanged(event){
            this.group_id = event.target.value
        },
        checkExportGroupErrors(){
            let has_error = false;
            if(this.sub_items.length){
                this.sub_items.some((value) => {
                    if((!value.group_id || value.group_id === 'no_group') && !value.deleted){
                        has_error =  true;
                    }
                })
            }
            if(this.children && this.children.length){
                this.children.some((value) => {
                    if((!value.group_id || value.group_id === 'no_group') && !value.deleted){
                        has_error =  true;
                    }
                })
            }

            return has_error
        },
        mainPositionClass(){
            let classname = '';
            if(this.sub_items.length || (this.children && this.children.length)){
                classname += ' has_children ';
            }else{
                classname += 'flex-wrap'
                if(!this.group_id || this.group_id === 'no_group'){
                    classname += ' not_choosed ';
                }
            }
            if(this.editable){
                classname += ' editable';
            }
            if(this.deleted){
                classname += ' deleted';
            }
            if(this.checkIfEditable()){
                classname += ' flex-wrap';
            }
            return classname;
        },

    },
}
</script>

<style scoped>

</style>
