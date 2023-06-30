<template>

    <div>
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
                <div class="breadcrumb-title pe-3">Зарплаты</div>
                <div class="d-flex align-items-center">
                    <div class="salary_main_datepicker_handler position-relative">
                        <div class="calendar_icon" @click="openMainDate"><i class="bx bx-calendar"></i></div>
                        <datepicker
                            ref="statistic_date"
                            :id="'main_salary_date_input'"
                            :input-class="'form-control'"
                            :placeholder="'Выберите дату'"
                            :language="language"
                            :monday-first="true"
                            minimum-view="month"
                            :format="'MMM yyyy'"
                            v-model="MAIN_DATE"
                        ></datepicker>
                    </div>

                    <button @click="resetMainDate()" class="button_no_nothing main_date_reset_handler"><i class="bx bx-refresh"></i></button>
                    <button @click="addNewWorker()" :class="['button_no_nothing main_date_reset_handler',{'active' : show_user_form}]"><i class="bx bx-user-plus"></i></button>
                </div>



            </div>

            <div v-show="!items_loaded">
                <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
            </div>
            <div v-show="items_loaded" :class="['card animate__animated animate__fadeIn',{'history':notCurrentMonth}]" id="salary_pay_tab">
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#salary_tab" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-add-to-queue font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Зарплаты</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3 ">
                        <div class="tab-pane fade active show costing_tab" id="salary_tab" role="tabpanel">
                            <div id="cast_wrapper" class="row">
                                <div class="col-12">

                                    <div class="card">

                                        <div class="card-body view_table_card">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="ps-2 text_date mb-0">{{showCurrentYear}}</h5>
                                                <div class="ps-2 salary_table_base_select_handler">
                                                    <vue-select :type="'base'"
                                                                :options="bases_to_select" v-model="base_id">
                                                        <option disabled value="0">Select one</option>
                                                    </vue-select>
                                                </div>
                                                <div class="ps-2">
                                                    <div class="position-relative salary_search">
                                                        <div class="wrapper">
                                                            <div class="control">
                                                                <input @focus="searchInputFocus" ref="search_input" type="search" v-model="search" :class="['control__input control__input--search',{'is-not-empty':search}]" />
                                                                <svg v-if="!search" class="control__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <circle cx="11" cy="11" r="8"></circle>
                                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                                </svg>
<!--                                                                <div v-if="search">
                                                                    <button @click="resetSearch(false)" class="button_no_nothing search_reset_button">
                                                                        <i class="bx bx-x"></i>
                                                                    </button>
                                                                </div>-->
                                                            </div>
                                                        </div>
                                                        <div class="salary_search_result_desk p-2" v-if="show_search_result_desk ">
                                                            <div v-if="searchResults.length">
                                                                <div  v-for="result in searchResults" class="d-flex salary_search_result justify-content-between" @click="searchReasulSelected(result.id)">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="bx bx-user"></i>
                                                                        <p>{{result.name}}</p>
                                                                    </div>
                                                                    <div class="category_base">
                                                                        <p>
                                                                            {{result.category.base.title + '/' + result.category.title}}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div v-else>
                                                                <div v-if="search">
                                                                    <p>Сотрудник не найден</p>
                                                                </div>
                                                                <div v-else>
                                                                    поиск
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="table_handler  fixer" id="black_fixer">
                                                <div class="d-flex align-items-center mb-2">
                                                    <h5 class="ps-2 text_date mb-0">{{showCurrentYear}}</h5>
                                                    <div class="ps-2 salary_table_base_select_handler">
                                                        <vue-select :type="'base'"
                                                                    :options="bases_to_select" v-model="base_id">
                                                            <option disabled value="0">Select one</option>
                                                        </vue-select>
                                                    </div>
                                                    <div class="ps-2">
                                                        <div class="position-relative salary_search">
                                                            <div class="wrapper">
                                                                <div class="control">
                                                                    <input @focus="searchInputFocus" type="search" ref="search_input_fixed" v-model="search" :class="['control__input control__input--search',{'is-not-empty':search}]" />
                                                                    <svg v-if="!search" class="control__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <circle cx="11" cy="11" r="8"></circle>
                                                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                                    </svg>
<!--                                                                    <div v-if="search">
                                                                        <button @click="resetSearch(true)" class="button_no_nothing search_reset_button">
                                                                            <i class="bx bx-x"></i>
                                                                        </button>
                                                                    </div>-->
                                                                </div>
                                                            </div>
                                                            <div class="salary_search_result_desk p-2" v-if="show_search_result_desk ">
                                                                <div v-if="searchResults.length">
                                                                    <div  v-for="result in searchResults" class="d-flex salary_search_result justify-content-between" @click="searchReasulSelected(result.id)">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="bx bx-user"></i>
                                                                            <p>{{result.name}}</p>
                                                                        </div>
                                                                        <div class="category_base">
                                                                            <p>
                                                                                {{result.category.base.title + '/' + result.category.title}}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div v-else>
                                                                    <div v-if="search">
                                                                        <p>Сотрудник не найден</p>
                                                                    </div>
                                                                    <div v-else>
                                                                        поиск
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table salary_workers_table">
                                                    <thead>
                                                    <tr class="same_design">
                                                        <th :style="'width:' + fixed_header_info.category + 'px'" class="cat" scope="col">Отдел</th>
                                                        <th :style="'width:' + fixed_header_info.name + 'px'" class="" scope="col" colspan="2">ФИО</th>
                                                        <th :style="'width:' + fixed_header_info.salary + 'px'" class="summary_salary" scope="col">Оклад</th>
                                                        <th :style="'width:' + fixed_header_info.factual_salary + 'px'" class="summary_factual_salary" scope="col">Зарплата</th>
                                                        <th :style="'width:' + fixed_header_info.paid + 'px'" class="summary_paid" scope="col">Выплачено</th>
                                                        <th :style="'width:' + fixed_header_info.remains + 'px'" class="summary_remains" scope="col">Остаток</th>
                                                        <th :style="'width:' + fixed_header_info.bonus + 'px'" class="summary_bonus" scope="col">Премия</th>
                                                        <th :style="'width:' + fixed_header_info.official_salary + 'px'" class="summary_official_salary" scope="col">Оф. Зарплата</th>
                                                        <th :style="'width:' + fixed_header_info.additional + 'px'" class="" scope="col">дополнительно</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="header_tr same_design" v-html="headerInfo()"></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table_handler" id="black_metal">
                                                <table class="table mb-0 table-bordered salary_workers_table" id="salary_main_table">
                                                    <thead>
                                                    <tr class="same_design">
                                                        <th ref="category" class="cat" scope="col">Отдел</th>
                                                        <th ref="name" class="" scope="col" colspan="2">ФИО</th>
                                                        <th ref="salary" class="summary_salary" scope="col">Оклад</th>
                                                        <th ref="factual_salary" class="summary_factual_salary" scope="col">Зарплата</th>
                                                        <th ref="paid" class="summary_paid" scope="col">Выплачено</th>
                                                        <th ref="remains" class="summary_remains" scope="col">Остаток</th>
                                                        <th ref="bonus" class="summary_bonus" scope="col">Премия</th>
                                                        <th ref="official_salary" class="summary_official_salary" scope="col">Оф. Зарплата</th>
                                                        <th ref="additional" class="" scope="col">дополнительно</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="header_tr same_design" v-html="headerInfo()"></tr>

                                                    <template v-for="(category,cat_id,cat_index) in workersToShow">
                                                        <tr v-for="(worker,index) in category.workers" @click="setEditWorker(worker.id, cat_id)"
                                                            :class="[{'active':worker.id == worker_id},workerTableColor(worker.id),cat_index %2 === 0 ?'even' : 'odd']">
                                                            <td v-if="index === 0" class="cat_rotate" :rowspan="category.workers.length"><div :class="rotateCategoryClass(category.workers.length)">{{category.category_name}}</div></td>
                                                            <td class="index"></td>
                                                            <td class="name">{{workers_info[worker.id].name}}</td>
                                                            <td class="salary">{{salaryText(worker.id)}}</td>
                                                            <td class="factual_salary">{{factualSalaryText(worker.id)}}</td>
                                                            <td class="already_paid">{{alreadyPaidText(worker.id)}}</td>
                                                            <td class="remains">{{salaryRemainderText(worker.id)}}</td>
                                                            <td class="bonus">{{paidBonusText(worker.id)}}</td>
                                                            <td class="official_salary">{{officialSalaryText(worker.id)}}</td>
                                                            <td class="already_paid">{{workers_info[worker.id].additional}}</td>
                                                        </tr>
                                                        {{updateTableFixer()}}
                                                    </template>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div>
                                        <div id="main_salary_edit_form" ref="form" :class="['animate__animated',show_form ? 'animate__fadeInRight' : 'animate__fadeOutRight']" >
                                            <div class="salary_form_hide_button_handler">
                                                <button class="button_no_nothing" @click="hide_form"> <i class="bx bx-x"></i></button>
                                            </div>
                                            <div class="row m-0">
                                                <div class="col-12 m-0 p-2">
                                                    <div class="card position_adding_card ">
                                                        <div class="card-body position-relative">
                                                            <div v-if="form_disabled" class="server_loading">
                                                                <div class="spinner-border text-info" role="status"> <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                            <div class="card-title">
                                                                <div class=" align-items-end vers_2_main">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="row date_main_part">
                                                                                        <div class="col-lg-6 col-12">
                                                                                            <div class="form-group ">
                                                                                                <label for="base">Площадка<span
                                                                                                    class="required_label">*</span></label>
                                                                                                <vue-select :type="'base'" ref="base_select"
                                                                                                            id="base"
                                                                                                            :force_value="sticky_base_id"
                                                                                                            :options="bases_to_select" v-model="base_id">
                                                                                                    <option disabled value="0">Select one</option>
                                                                                                </vue-select>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-6 col-12">
                                                                                            <div class="form-group">
                                                                                                <div class="position-relative">
                                                                                                    <label for="category">Отдел<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <vue-select ref="category_select" id="category" :force_value="sticky_category_id" :options="categories_to_select" v-model="category_id">
                                                                                                        <option disabled value="0">Select one</option>
                                                                                                    </vue-select>
                                                                                                    <span v-if="!categories_to_select.length" class="unique_title_alarm">в данной базе нет отделов</span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <div class="form-group">
                                                                                                <div class="position-relative">
                                                                                                    <label for="name">Сотрудник<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <vue-select ref="category_select" :id="'name'" :force_value="sticky_worker_id" :options="workers_to_select" v-model="worker_id">
                                                                                                        <option disabled value="0">Select one</option>
                                                                                                    </vue-select>
                                                                                                    <span v-if="!workers_to_select.length" class="unique_title_alarm">в данном отделе нет сотрудников</span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <div class="form-group">
                                                                                                <div class="position-relative">

                                                                                                    <div class="row mt-2">
                                                                                                        <div class="col-4">
                                                                                                            <div :class="['event_card salary_card',{'active':event_id === 'salary'}]" @click="setEvent('salary')">
                                                                                                                Зарплата
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-4">
                                                                                                            <div :class="['event_card holiday_card',{'active':event_id === 'holiday'}]" @click="setEvent('holiday')">
                                                                                                                Отпуск
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-4">
                                                                                                            <div :class="['event_card day_off_card',{'active':event_id === 'day_off'}]" @click="setEvent('day_off')">
                                                                                                                Выходной
                                                                                                            </div>
                                                                                                        </div>
<!--                                                                                                        <div class="col-4 mt-2">
                                                                                                            <div :class="['event_card increase_card',{'active':event_id === 'increase'}]" @click="setEvent('increase')">
                                                                                                                Прибавка
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-4 mt-2">
                                                                                                            <div :class="['event_card firing_card',{'active':event_id === 'firing'}]" @click="setEvent('firing')">
                                                                                                                Увольнение
                                                                                                            </div>
                                                                                                        </div>-->
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="text-dark mt-3">
                                                                                    <div class="d-flex align-items-center justify-content-between">

                                                                                        <h5 class="text-dark text-decoration-underline" v-if="current_worker"><a class="text-dark" :href="'/workers/homepage?id=' + current_worker.id">{{current_worker.name}}</a><span v-if="startOfWork(null,true)" class="start_of_work_span">работает с <span>{{startOfWork(null,true)}}</span></span></h5>

                                                                                        <h6 :class="['ps-2',currentDateSignColor]">{{showCurrentYear}}</h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div v-if="event_id === 'salary'">
                                                                                    <div class="col-12">
                                                                                        <div :class="['position-relative salary_progress has_tooltips mt-2',{'active':progress_active}]">
                                                                                            <div class="progress" style="height:7px;">
                                                                                                <div :class="['progress-bar progress-bar-striped progress-bar-animated', progressBar.background]" role="progressbar" :style="{width:progressBar.percent + '%'}" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                            </div>
                                                                                            <div v-for="(tooltip,name,index) in tooltipList()" :class="['salary_tooltip too_right',{'too_right':tooltip.left > 95 || (tooltip.left > 85 && index !== tooltipList.length-1)}]" :style="'left:' + tooltip.left + '%'" @click="salaryTooltipClicked(tooltip.id)">
                                                                                                <div class="tooltip_card">
                                                                                                    <div class="title_handler">
                                                                                                        <div class="date">{{tooltip.date}}</div>
                                                                                                        {{tooltip.amount}}
                                                                                                    </div>

                                                                                                    <div class="caret_handler">
                                                                                                        <span></span>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row mb-2 salary_calculate_info">

                                                                                            <div class="col-4">
                                                                                                <div class="row">
                                                                                                    <div class="col-12 text-center salaryText">
                                                                                                        <label>
                                                                                                            Оклад
                                                                                                        </label>
                                                                                                        <h6>
                                                                                                            {{salaryText()}}
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <div class="position-relative col-12 text-center factualSalaryText">
                                                                                                        <label>
                                                                                                            Зарплата
                                                                                                        </label>
                                                                                                        <div v-show="current_worker.official_salary && !officialPaid() && !official_salary_set" class="position-absolute btn-group right-15">
                                                                                                            <button type="button" class="button_no_nothing text-dark fs-6" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-horizontal-rounded"></i></button>
                                                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" style="margin: 0px;">
                                                                                                                <span style="white-space: nowrap;" class="p-2">Выплатить оф. зарплату</span>
                                                                                                                <div class="dropdown-divider"></div>
                                                                                                                <a class="dropdown-item text-center fs-6" @click="setOfficialSalary" role="button">{{makeMoney(current_worker.official_salary,true)}}</a>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <h6>
                                                                                                            {{factualSalaryText()}}
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <div class="text-center alreadyPaidText">
                                                                                                    <label>
                                                                                                        Выплачено
                                                                                                    </label>
                                                                                                    <h6>
                                                                                                        {{alreadyPaidText()}}
                                                                                                    </h6>
                                                                                                    <div class="salary_list">
                                                                                                        <div v-for="salary in salariesList()" :class="['list_salary_item d-flex justify-content-between',{'deleted':salary.deleted}]">
                                                                                                            <span class="salary">
                                                                                                                <input
                                                                                                                    :disabled="salary.deleted"
                                                                                                                    type="text"
                                                                                                                    :ref="'old_salary' + salary.id"
                                                                                                                    @input="oldSalaryChanged($event, salary.id)"
                                                                                                                    :class="['small_input',{'official':salary.official}]"
                                                                                                                    :value="salary.amount">
                                                                                                                <span>₽</span>
                                                                                                            </span>
                                                                                                            <div class="d-flex">
                                                                                                                <span class="date">{{salary.date}}</span>
                                                                                                                <button v-if="!salary.deleted"
                                                                                                                        @click="deleteOldSalary(salary.id)"
                                                                                                                        :class="['button_no_nothing salary_delete_button',]">
                                                                                                                    <i :class="['bx bx-trash', {'text_day_off':salary.official}]"></i>
                                                                                                                </button>
                                                                                                                <button v-if="salary.deleted"
                                                                                                                        @click="restoreOldSalary(salary.id)"
                                                                                                                        class="button_no_nothing salary_restore_button">
                                                                                                                    <i class="bx bx-undo"></i>
                                                                                                                </button>
                                                                                                            </div>

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <div class="text-center paidBonusText">
                                                                                                    <label>
                                                                                                        Премия
                                                                                                    </label>
                                                                                                    <h6>
                                                                                                        {{paidBonusText()}}
                                                                                                    </h6>
                                                                                                    <div class="salary_list">
                                                                                                        <div v-for="salary in bonusesList()" :class="['list_salary_item d-flex justify-content-between',{'deleted':salary.deleted}]">
                                                                                                            <span class="bonus">
                                                                                                                <input
                                                                                                                    :disabled="salary.deleted"
                                                                                                                    type="text"
                                                                                                                    :ref="'old_bonus' + salary.id"
                                                                                                                    @input="oldBonusChanged($event, salary.id)"
                                                                                                                    class="small_input"
                                                                                                                    :value="salary.amount">
                                                                                                                <span>₽</span>
                                                                                                            </span>
                                                                                                            <div class="d-flex">
                                                                                                                <span class="date">{{salary.date}}</span>
                                                                                                                <button v-if="!salary.deleted"
                                                                                                                        @click="deleteOldSalary(salary.id)"
                                                                                                                        class="button_no_nothing salary_delete_button">
                                                                                                                    <i class="bx bx-trash"></i>
                                                                                                                </button>
                                                                                                                <button v-if="salary.deleted"
                                                                                                                        @click="restoreOldSalary(salary.id)"
                                                                                                                        class="button_no_nothing salary_restore_button">
                                                                                                                    <i class="bx bx-undo"></i>
                                                                                                                </button>
                                                                                                            </div>

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>


                                                                                            <div class="col-4">
                                                                                                <div :class="['col-12 text-center remainderText',{'error':paidMoreThanNeeded()} ]">
                                                                                                    <label>
                                                                                                        Остаток
                                                                                                    </label>
                                                                                                    <div>
                                                                                                        <h6 @click="autoFillRemainder()">
                                                                                                            {{salaryRemainderText()}}
                                                                                                        </h6>

                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <div class="col-12 text-center extraHolidayText">
                                                                                                    <label>
                                                                                                        Отпуск
                                                                                                    </label>
                                                                                                    <h6 class="set_event_er in_tab" @click="setEvent('holiday')">
                                                                                                        {{extraHolidayText(null,true)}}/{{extraHolidayText()}}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-4">
                                                                                                <div class="col-12 text-center dayOffText">
                                                                                                    <label>
                                                                                                        Выходные
                                                                                                    </label>
                                                                                                    <h6 class="set_event_er in_tab" @click="setEvent('day_off')">
                                                                                                        {{dayOffText(null,true)}}/{{dayOffText()}}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>


                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 mt-3">
                                                                                        <div class="row date_main_part">
                                                                                            <div class="col-12 col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <div class="d-flex justify-content-between">
                                                                                                        <label for="salary">Выплатить
                                                                                                            <span class="required_label">*</span>

                                                                                                        </label>
                                                                                                        <button class="in_tab border-official-salary button_no_nothing has_hover_sign" @click="resetOfficialSalary" v-if="official_salary_set"><small>Оф</small><small class="show_on_hover in_tab">x</small></button>
                                                                                                    </div>
                                                                                                    <div class="position-relative">
                                                                                                        <input v-model="salary" required
                                                                                                               :disabled="official_salary_set"
                                                                                                               ref="salary_input"
                                                                                                               type="text"
                                                                                                               placeholder="Введите сумму"
                                                                                                               autocomplete="off"
                                                                                                               :class="['form-control sum_input',{'official_salary_set':official_salary_set}]" id="salary"
                                                                                                               @keyup="salaryChanged"
                                                                                                               @change="salaryChanged"
                                                                                                               @keydown="salaryKeyDown"
                                                                                                        >
                                                                                                        <button v-if="!official_salary_set" @click="resetSalary" class="button_no_nothing salary_reset_button"><i class="bx bx-x"></i></button>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-12 col-lg-4">
                                                                                                <div class="form-group">
                                                                                                    <div class="d-flex justify-content-between">
                                                                                                        <label for="bonus">Премия<span
                                                                                                            class="required_label">*</span> </label>
                                                                                                    </div>
                                                                                                    <div class="position-relative">
                                                                                                        <input v-model="bonus" required type="text"
                                                                                                               placeholder="Введите сумму"
                                                                                                               autocomplete="off"
                                                                                                               class="form-control sum_input" id="bonus"
                                                                                                               @keyup="bonusChanged"
                                                                                                               @keydown="salaryKeyDown"
                                                                                                        >
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-12 col-lg-4">
                                                                                                <div class="form-group ">
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <label class="cursor-pointer" @click="setSalaryDate('today')">Дата<span
                                                                                                            class="required_label">*</span></label>
                                                                                                        <span class="day_setter day_1" @click="setSalaryDate('half')">15</span>
                                                                                                        <span class="day_setter day_15" @click="setSalaryDate('full')">30</span>
                                                                                                    </div>

                                                                                                    <datepicker
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="date">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div v-else-if="event_id === 'holiday'">
                                                                                    <div class="col-12 mt-3">
                                                                                        <div class="text-dark date_main_part" >
                                                                                            <div v-if="holidaysList().length">
                                                                                                <table  class="table holiday_table table-sm mb-0">
                                                                                                    <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">От</th>
                                                                                                        <th scope="col">До</th>
                                                                                                        <th scope="col">Оплачиваемый</th>
                                                                                                    </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    <tr v-for="holiday in holidaysList()">
                                                                                                        <td>
                                                                                                            <datepicker

                                                                                                                :id="'worker_holiday_date_from'"
                                                                                                                ref="date_input"
                                                                                                                :input-class="'form-control'"
                                                                                                                :placeholder="'Выберите дату'"
                                                                                                                :language="language"
                                                                                                                @input="oldHolidayStartDateChanged($event, holiday.id)"
                                                                                                                :monday-first="true"
                                                                                                                :value="new Date(holiday.start_date)">

                                                                                                            </datepicker>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <datepicker

                                                                                                                :id="'worker_holiday_date_from'"
                                                                                                                ref="date_input"
                                                                                                                :input-class="'form-control'"
                                                                                                                :placeholder="'Выберите дату'"
                                                                                                                :language="language"
                                                                                                                :monday-first="true"
                                                                                                                @input="oldHolidayEndDateChanged($event, holiday.id)"
                                                                                                                :value="holiday.end_date ? new Date(holiday.end_date) : null">

                                                                                                            </datepicker>
                                                                                                        </td>
                                                                                                        <td><input
                                                                                                            @change="oldHolidayPaidChanged($event, holiday.id)"
                                                                                                            v-model="holiday.paid"
                                                                                                            type="checkbox"></td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>

                                                                                            <div v-else>
                                                                                                Нет отпусков
                                                                                            </div>


                                                                                        </div>
                                                                                        <div class="row date_main_part text-dark mt-2">
                                                                                            <h6>Отправить в отпуск</h6>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_holiday_date_from">От<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <datepicker

                                                                                                        :id="'worker_holiday_date_from'"
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="holiday_date.from">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_holiday_date_to">До</label>
                                                                                                    <datepicker
                                                                                                        :id="'worker_holiday_date_to'"
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="holiday_date.to">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div v-else-if="event_id === 'day_off'">
                                                                                    <div class="col-12 mt-3">
                                                                                        <div class="text-dark date_main_part" >

                                                                                            <div v-if="dayOffList().length">
                                                                                                <table class="table holiday_table table-sm mb-0">
                                                                                                    <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">Дата</th>
                                                                                                        <th scope="col">кол дней</th>
                                                                                                        <th scope="col">Оплачиваемый</th>
                                                                                                    </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    <tr v-for="old_day_off in dayOffList()">
                                                                                                        <td>
                                                                                                            <datepicker

                                                                                                                :id="'worker_holiday_date_from'"
                                                                                                                ref="date_input"
                                                                                                                :input-class="'form-control'"
                                                                                                                :placeholder="'Выберите дату'"
                                                                                                                :language="language"
                                                                                                                @input="oldHolidayStartDateChanged($event, old_day_off.id)"
                                                                                                                :monday-first="true"
                                                                                                                :value="new Date(old_day_off.start_date)">

                                                                                                            </datepicker>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input
                                                                                                                type="number"
                                                                                                                v-model="old_day_off.additional.days"
                                                                                                                @change="dayOffLengthChanged($event, old_day_off.id)"
                                                                                                            >
                                                                                                        </td>
                                                                                                        <td><input
                                                                                                            @change="oldHolidayPaidChanged($event, old_day_off.id)"
                                                                                                            v-model="old_day_off.paid"
                                                                                                            type="checkbox"></td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div v-else>
                                                                                                Нет выходных
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row date_main_part text-dark mt-2">
                                                                                            <h6>Выходной</h6>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_day_off_date">Дата<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <datepicker

                                                                                                        :id="'worker_day_off_date'"
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="day_off.date">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_day_off_date_to">Кол. дней</label>
                                                                                                    <input type="number"  v-model="day_off.length" id="worker_day_off_date_to" class="form-control">
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
<!--                                                                                <div v-else-if="event_id === 'increase'">
                                                                                    <div class="col-12 mt-3">
                                                                                        <div class="text-dark date_main_part" >

                                                                                            <div v-if="increasesList().length">
                                                                                                <table class="table holiday_table table-sm mb-0">
                                                                                                    <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">Дата</th>
                                                                                                        <th scope="col">Нов. зарплата</th>

                                                                                                    </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    <tr v-for="old_increase in increasesList()">
                                                                                                        <td>
                                                                                                            <datepicker

                                                                                                                :id="'worker_holiday_date_from'"
                                                                                                                :input-class="'form-control'"
                                                                                                                :placeholder="'Выберите дату'"
                                                                                                                :language="language"
                                                                                                                @input="oldIncreaseDateChanged($event, old_increase.id)"
                                                                                                                :monday-first="true"
                                                                                                                :value="old_increase.date">

                                                                                                            </datepicker>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                :value="old_increase.salary"
                                                                                                                @input="oldIncreaseAmountChanged($event, old_increase.id)"
                                                                                                            >
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div v-else>
                                                                                                Нет прибавок
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row date_main_part text-dark mt-2">
                                                                                            <h6>Прибавка</h6>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_increase_date">Дата<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <datepicker

                                                                                                        :id="'worker_day_off_date'"
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="increase.date">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_increase_date_to">Новая Зарплата</label>
                                                                                                    <input type="text" @keyup="increaseChanged"  v-model="increase.salary" id="worker_increase_date_to" class="form-control">
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div v-else-if="event_id === 'firing'">
                                                                                    <div class="col-12 mt-3">
                                                                                        <div v-if="firingList().length" class="text-dark date_main_part" >
                                                                                            <div>
                                                                                                <table class="table holiday_table firing_table table-sm mb-0">
                                                                                                    <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">Дата</th>
                                                                                                        <th scope="col">Комментарий</th>

                                                                                                    </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    <tr v-for="old_firing in firingList()">
                                                                                                        <td>
                                                                                                            <datepicker

                                                                                                                :id="'worker_holiday_date_from'"
                                                                                                                :input-class="'form-control'"
                                                                                                                :placeholder="'Выберите дату'"
                                                                                                                :language="language"
                                                                                                                @input="oldFiringDateChanged($event, old_firing.id)"
                                                                                                                :monday-first="true"
                                                                                                                :value="old_firing.date">

                                                                                                            </datepicker>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <textarea
                                                                                                                @input="oldFiringCommentChanged($event, old_firing.id)"
                                                                                                                v-model="old_firing.additional"></textarea>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row date_main_part text-dark mt-2">
                                                                                            <h6>Увольнение</h6>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_firing_date">Дата<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <datepicker

                                                                                                        :id="'worker_firing_date'"
                                                                                                        ref="date_input"
                                                                                                        :input-class="'form-control'"
                                                                                                        :placeholder="'Выберите дату'"
                                                                                                        :language="language"
                                                                                                        :monday-first="true"
                                                                                                        v-model="firing.date">

                                                                                                    </datepicker>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-12 col-lg-6">
                                                                                                <div class="form-group ">
                                                                                                    <label for="worker_firing_comment">Комментарий</label>
                                                                                                    <textarea  v-model="firing.comment" id="worker_firing_comment" class="form-control"></textarea>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                </div>
                                                                <div class="d-flex mt-4 justify-content-between">
                                                                    <div>
                                                                        <button @click="submitForm"
                                                                                :disabled="form_disabled"
                                                                                type="button"
                                                                                :class="['btn btn-light pos_save text-white ms-3',event_id]">
                                                                            Сохранить<i class="bx bx-save"></i></button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 ">
                                    <div>
                                        <div id="main_worker_edit_form" ref="form" :class="['animate__animated',show_user_form ? 'animate__fadeInRight' : 'animate__fadeOutRight']" >
                                            <div class="salary_form_hide_button_handler">
                                                <button class="button_no_nothing" @click="hide_worker_form"> <i class="bx bx-x"></i></button>
                                            </div>
                                            <div class="row m-0">
                                                <div class="col-12 m-0 p-2">
                                                    <div class="card position_adding_card ">
                                                        <div class="card-body position-relative">
                                                            <div v-if="form_disabled" class="server_loading">
                                                                <div class="spinner-border text-info" role="status"> <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                            <div class="card-title">
                                                                <h6 class="text-dark">
                                                                    Добавить нового сотрудника
                                                                </h6>

                                                                <div class=" align-items-end vers_2_main">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="row date_main_part">
                                                                                        <div class="col-lg-6 col-12">
                                                                                            <div class="form-group ">
                                                                                                <label for="worker_base">База<span
                                                                                                    class="required_label">*</span></label>
                                                                                                <vue-select :type="'base'" ref="base_select"
                                                                                                            id="worker_base"
                                                                                                            :options="hire_bases_to_select" v-model="hire_base_id">
                                                                                                    <option disabled value="0">Select one</option>
                                                                                                </vue-select>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-6 col-12">
                                                                                            <div class="form-group ">
                                                                                                <div class="position-relative">
                                                                                                    <label for="worker_category">Отдел<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <vue-select ref="category_select" id="worker_category"  :options="hire_categories_to_select" v-model="hire_category_id">
                                                                                                        <option disabled value="0">Select one</option>
                                                                                                    </vue-select>

                                                                                                    <span v-if="!hire_categories_to_select.length" class="unique_title_alarm">в данной базе нет отделов</span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <div class="form-group">
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <label for="name">Имя<span
                                                                                                        class="required_label">*</span>
                                                                                                    </label>

                                                                                                </div>
                                                                                                <div class="position-relative">
                                                                                                    <input autocomplete="off" v-model="hire_worker.name" required ref="name_input" type="text"
                                                                                                           class="form-control" id="name">
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="row date_main_part">
                                                                                        <div class="col-12 col-lg-6">
                                                                                            <div class="form-group h-100 d-flex flex-column justify-content-between">
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <label for="hire_salary">Зарплата </label>
                                                                                                </div>
                                                                                                <div class="position-relative">
                                                                                                    <input v-model="hire_worker.salary" required  type="text"
                                                                                                           placeholder="Введите сумму"
                                                                                                           class="form-control sum_input" id="hire_salary"
                                                                                                           @keyup="hireSalaryChanged"
                                                                                                    >
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12 col-lg-6">
                                                                                            <div class="form-group">
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <label for="official_salary">Офиц. Зарплата </label>
                                                                                                </div>
                                                                                                <div class="position-relative">
                                                                                                    <input v-model="hire_worker.official_salary" required type="text"
                                                                                                           placeholder="Введите сумму"
                                                                                                           class="form-control sum_input" id="official_salary"
                                                                                                           @keyup="officialSalaryChanged"
                                                                                                    >
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12 col-lg-6">
                                                                                            <div class="form-group">
                                                                                                <div class="position-relative">
                                                                                                    <label for="worker_category">Должность<span
                                                                                                        class="required_label">*</span></label>
                                                                                                    <vue-select ref="category_select" id="posts_select"  :options="posts_to_select" v-model="hire_worker.post_id">
                                                                                                        <option disabled value="0">Select one</option>
                                                                                                    </vue-select>

                                                                                                    <span v-if="!posts_to_select.length" class="unique_title_alarm">в данной базе нет отделов</span>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12 col-lg-6">
                                                                                            <div class="form-group ">
                                                                                                <label >Дата Принятия<span
                                                                                                    class="required_label">*</span></label>
                                                                                                <datepicker
                                                                                                    :id="'worker_adding_date'"
                                                                                                    ref="hire_date"
                                                                                                    :placeholder="'Выберите дату'"
                                                                                                    :language="language"
                                                                                                    :monday-first="true"
                                                                                                    v-model="hire_worker.date">

                                                                                                </datepicker>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12 ">
                                                                                            <div class="form-group">
                                                                                                <label for="comment">Дополнительно</label>
                                                                                                <textarea id="comment" name="comment"
                                                                                                          v-model="hire_worker.additional"
                                                                                                          class="form-control comment_input" cols="30"
                                                                                                          rows="4"></textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                </div>
                                                                <div class="d-flex mt-4 justify-content-between">
                                                                    <div>
                                                                        <button @click="submitHireForm"
                                                                                :disabled="form_disabled"
                                                                                type="button"
                                                                                :class="['btn btn-light pos_save text-white ms-3']">
                                                                            Сохранить<i class="bx bx-save"></i></button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

</template>
<script>
import Select2Component from "../select2/Select2Component";
import Datepicker from "vuejs-datepicker";

export default {
    name: "salary-component",
    data:function () {
        return {
            items_loaded:false,
            show_form:false,
            show_user_form:false,
            language: vdp_translation_ru.js,
            hire_worker:{
                name:null,
                date:new Date(),
                official_salary:null,
                salary:null,
                additional:null,
                post_id:null,
            },
            /*all bases with categories with workers*/
            bases: {},
            hire_bases:{},
            current_worker:{
                events:[],
                salaries:[],
                increases:[],
            },
            day_off:{
                date:null,
                length:null,
            },
            increase:{
                date:null,
                salary:null,
            },
            firing:{
                date:null,
                comment:null
            },
            base_id:null,
            category_id:null,
            worker_id:null,
            force_worker_already_set:false,
            fixed_header_info:{
                category:null,
                name:null,
                salary:null,
                factual_salary:null,
                paid:null,
                remains:null,
                bonus:null,
                additional:null,
                official_salary:null,
            },

            hire_base_id:null,
            hire_category_id:null,

            sticky_base_id:null,
            sticky_category_id:null,
            sticky_worker_id:null,


            event_id:this.setInitialEvent(),
            date:null,

            holiday_date:{
                from:null,
                to:null
            },
            MAIN_DATE:this.setInitialMainDate(),
            salary:null,
            bonus:null,
            official_salary_set:false,
            holiday_days:null,
            /*for select*/
            bases_to_select:[],
            categories_to_select:[{id:'no_id', text:'Нет Отделов'}],
            workers_to_select:[{id:'no_id', text:'Нет Сотрудников'}],
            hire_bases_to_select:[],
            hire_categories_to_select:[],
            posts_to_select:[],
            search:null,
            show_search_result_desk:false,
            workers_info:{},

            table_workers:[],

            table_current_page:1,
            workers_per_page:10,
            form_disabled:false,
            progress_show_timeout:null,
            progress_active:false,
        }

    },
    updated() {
        this.updateTableFixer()

    },
    watch:{
        current_worker:{
            handler:function (value){
            },
            deep:true
        },
        base_id:function (value){

            if(value){
                this.categories_to_select = this.bases[value].options
                this.table_workers = this.bases[this.base_id].categories
                /*let force_value = this.sticky_base_id;
                let storage_base_id = this.getWorkersIndexes().base_id;
                if(force_value && force_value != value && force_value !== storage_base_id ){
                    this.sticky_base_id = null;
                    this.sticky_category_id = null;
                    this.sticky_worker_id = null;
                }*/
            }
        },
        category_id:function (value){
            if(value){
                /*let force_value = this.sticky_category_id;
                let storage_category_id = this.getWorkersIndexes().category_id;
                if(force_value && force_value != value && force_value !== storage_category_id){
                    this.sticky_category_id = null;
                    this.sticky_worker_id = null;
                }*/
                this.workers_to_select = this.bases[this.base_id].categories[value].options
            }


        },
        worker_id:function (value){
            if(value){
               /* let force_value = this.sticky_worker_id;
                let storage_worker_id = this.getWorkersIndexes().worker_id;
                if(force_value && force_value != value && force_value !== storage_worker_id){
                    this.sticky_worker_id = null;
                }*/
                this.current_worker =  this.workers_info[this.worker_id];
                this.setTodaysSalaryAmount()
            }

        },
        hire_base_id:function (value){
            this.hire_categories_to_select = this.hire_bases[value].options
        },
        form_disabled:function (value){

        },

        event_id:function (value){

        },
        salary:function (value){

        },
        date:function (value){
            this.setTodaysSalaryAmount()
        },
        MAIN_DATE:function (value){
            this.updateAllData()
            this.date = new Date(value)
        },
        workers_info:function (value){
            let last_worker_id = localStorage.getItem('worker_id')
            if(last_worker_id){
                let worker = this.workers_info[last_worker_id]
                if(worker){
                    this.setForceWorker(worker);
                }
            }

        },
        items_loaded:function (){
            this.$nextTick(function (){
                this.createTableFixer()
            })

        },
        table_workers:{
            handler:function (){
                this.$nextTick(() => {

                })
            },
            deep:true
        }
    },
    components: {
        Select2Component,
        Datepicker
    },
    created() {
        this.updateAllData()
    },
    mounted() {

        window.onresize = this.updateTableFixer()
        if(this.notCurrentMonth){
            this.date = new Date(this.MAIN_DATE.setDate(1))
        }else{
            this.date = new Date(this.MAIN_DATE)
        }

        $(document).on('click', event => {
            if (!$(event.target).closest('#salary_tab').length && !$(event.target).closest('.in_tab').length && !$(event.target).closest('.select2-container').length && !$(event.target).closest('.salary_search_result_desk').length) {
                this.show_form = false;
            }
            if (!$(event.target).closest('#main_worker_edit_form').length && !$(event.target).closest('.main_date_reset_handler').length && !$(event.target).closest('.select2-container').length) {
                this.show_user_form = false;
            }
            if (!$(event.target).closest('.salary_search').length && !$(event.target).closest('.search_reset_button').length) {
                this.show_search_result_desk = false;

            }
        });
    },
    computed:{
        currentDateSignColor(){
            if(this.event_id === 'salary' || this.event_id === 'holiday'){
                return 'text_date'
            }else if(this.event_id === 'day_off'){
                return 'text_day_off'
            }else if(this.event_id === 'increase'){
                return 'text_increase'
            }else if(this.event_id === 'firing'){
                return 'text_firing'
            }
        },
        searchResults(){
            let text = this.search;
            let results = [];
            if(text) {
                results = Object.values(this.workers_info).filter(value => {
                    return value.name.toUpperCase().indexOf(text.toUpperCase()) > -1
                })

            }
            return results.slice(0,5)
        },
        current_base_title(){

            let title = ''
            if(this.base_id){
                let base = this.bases_to_select.find(value => {
                    if(value.id === parseInt(this.base_id)){
                        return true
                    }
                })
                title = base.text
            }
            return title
        },
        progressBar(){
            let percent = 0;
            let background = '';
            let worker = this.current_worker;
            if(worker){
                let already_paid = this.alreadyPaidText(worker.id,true)
                let salary = this.factualSalaryText(worker.id,true)
                percent = Math.floor((already_paid * 100) /salary)
                if(already_paid > salary){
                    percent = 100;
                    background = 'too_much'
                }else if(percent === 100){
                    background = 'ok'
                }else if(percent >= 50){
                    background = 'half'
                }else{
                }
            }

            return {percent:percent, background:background}
        },
        /*date for link to update bases when main date changed*/
        getCalculateYear(){
            let year = this.MAIN_DATE.getFullYear();
            let month = this.MAIN_DATE.getMonth() + 1;
            return year + '-' + month
        },
        notCurrentMonth(){
            let year = this.MAIN_DATE.getFullYear();
            let month = this.MAIN_DATE.getMonth();

            let current_year = (new Date()).getFullYear()
            let current_month = (new Date()).getMonth()
            return current_year !== year || current_month !== month
        },
        showCurrentYear(){
            let months = [
                'Янв',
                'Февр',
                'Март',
                'Апр',
                'Май',
                'Июнь',
                'Июль',
                'Авг',
                'Сент',
                'Окт',
                'Нояб',
                'Дек',
            ];
            let year = this.MAIN_DATE.getFullYear();
            let month = this.MAIN_DATE.getMonth();
            return months[month] + ' ' + year;
        },
        workersToShow(){

            return this.table_workers
        },

    },
    methods: {
        officialPaid(worker_id = null){
            let worker = worker_id ? this.workers_info[worker_id] : this.current_worker
            return worker.salaries.filter(value => value.additional && value.additional.official_salary).length
        },
        setInitialMainDate(){
            let date = localStorage.getItem('date');
            if(date){
                localStorage.removeItem('date');
                return new Date(date)
            }
            return new Date()
        },
        focusInitialSalary(){
            let focusable_id = localStorage.getItem('salary_to_focus')
            if(focusable_id){
                if(this.$refs['old_salary' + focusable_id]){
                    this.$refs['old_salary' + focusable_id][0].focus()
                    localStorage.removeItem('salary_to_focus')
                }else if(this.$refs['old_bonus' + focusable_id]){
                    this.$refs['old_bonus' + focusable_id][0].focus()
                    localStorage.removeItem('salary_to_focus')
                }
            }
        },
        setInitialEvent(){
            let event = localStorage.getItem('event');
            if(event){
                localStorage.removeItem('event');
                return event
            }
            return 'salary'

        },
        checkForceWorkerSetStatus(){
        },
        setForceWorker(worker){
            console.log('setForceWorker')
            let indexes = this.getWorkersIndexes(worker);
            this.sticky_base_id = indexes.base_id
            this.sticky_category_id = indexes.category_id
            this.sticky_worker_id = indexes.id
            this.show_form = true;
            this.show_search_result_desk = false;

        },
        getWorkersIndexes(worker = null){
            let worker_obj = null;
            if(worker || localStorage.getItem('worker_id')){
                worker_obj = worker ? worker : this.workers_info[localStorage.getItem('worker_id')]
                if(worker_obj){
                    return {id:worker_obj.id,category_id:worker_obj.category_id, base_id:worker_obj.category.base_id};
                }

            }
            return {id:null,category_id:null, base_id:null};
        },
        searchInputFocus(){
            this.show_search_result_desk = true;
        },
        resetSearch(fixed = false){
            this.search = null
            if(fixed){
                this.$refs.search_input_fixed.focus()
            }else{
                this.$refs.search_input.focus()
            }
            this.show_search_result_desk = true;
        },
        searchReasulSelected(worker_id){
            let worker = this.workers_info[worker_id];
            if(worker){
                this.search = worker.name
                localStorage.setItem('worker_id',worker.id)
                this.setForceWorker(worker)
            }
        },
        salaryTooltipClicked(ref){
            this.$refs['old_salary' + ref][0].focus()
        },
        setTodaysSalaryAmount(){
            let already_paid = this.alreadyPaidText(null,true)
            let factual_salary = this.factualSalaryText(null,true)
            let per_day = Math.ceil(factual_salary/3000)*100
            let current = per_day * this.date.getDate() - already_paid;
            if(current + already_paid > factual_salary || this.daysInMonth(this.date.getMonth() + 1,this.date.getFullYear()) === this.date.getDate()){
                current = factual_salary - already_paid
            }else if(this.date.getDate() === 15){
                current = Math.floor(factual_salary / 2) - already_paid
            }
            if(current <= 0){
                current = null;
            }
            this.salary = this.makeMoney(current)
        },
        setOfficialSalary(){
            this.salary = this.makeMoney(this.current_worker.official_salary)
            this.official_salary_set = true
        },
        resetSalary(){
            this.salary = null;
        },
        resetOfficialSalary(){
            this.salary = null;
            this.official_salary_set = false
        },
        createTableFixer(){
            let NAVBAR_HEIGHT = $('.navbar ').outerHeight();

            $('#black_fixer table').width($('#black_metal table').innerWidth())
            $('#black_fixer').width($('#black_metal').innerWidth())

            function checkTableHeads(){
                let black_height = $('#black_metal table').outerHeight();
                let black_fixer_height = $('#black_metal thead').height();
                let should_height_black = black_height - black_fixer_height - NAVBAR_HEIGHT;
                let black_top = $('#black_metal table').offset().top;


                if($(document).scrollTop() > black_top - NAVBAR_HEIGHT + 100 && $(document).scrollTop() < black_top + should_height_black){
                    $('#black_fixer').addClass('fixed');
                }else{
                    $('#black_fixer').removeClass('fixed');
                }
            }
            $(window).on('resize',function (){
                $('#black_fixer table').width($('#black_metal table').innerWidth())
                $('#black_fixer').width($('#black_metal').innerWidth())
                $('#black_metal thead th').each(function (index){
                    $('#black_fixer thead th').eq(index).css('width',$(this).innerWidth())
                })
            })
            $("#black_metal").on("scroll", function (e) {
                $('#black_fixer').stop().animate({
                    scrollLeft:e.currentTarget.scrollLeft
                },0)
            });
            $("#black_fixer").on("scroll", function (e) {
                $('#black_metal').stop().animate({
                    scrollLeft:e.currentTarget.scrollLeft
                },0)
            });
            $(window).scroll(function() {
                checkTableHeads();
            });
        },
        updateTableFixer() {
            this.$nextTick(function (){
                this.fixed_header_info.category = this.$refs.category.offsetWidth
                this.fixed_header_info.name = this.$refs.name.offsetWidth
                this.fixed_header_info.salary = this.$refs.salary.offsetWidth
                this.fixed_header_info.factual_salary = this.$refs.factual_salary.offsetWidth
                this.fixed_header_info.paid = this.$refs.paid.offsetWidth
                this.fixed_header_info.remains = this.$refs.remains.offsetWidth
                this.fixed_header_info.bonus = this.$refs.bonus.offsetWidth
                this.fixed_header_info.additional = this.$refs.additional.offsetWidth
                this.fixed_header_info.official_salary = this.$refs.official_salary.offsetWidth
            })

        },

        daysInMonth (month, year) {
            return new Date(year, month, 0).getDate();

        },
        setSalaryDate(value){
            if(value === 'today'){
                let day = new Date().getDate();
                this.date = new Date(this.date.setDate(day))
            }else if(value === 'half'){
                this.date = new Date(this.date.setDate(15))
            }else{
                this.date = new Date(this.date.setDate(this.daysInMonth(this.date.getMonth() + 1,this.date.getFullYear())))
            }

        },
        headerInfo(){
            let summary_salary = 0;
            let summary_factual_salary = 0;
            let summary_paid = 0;
            let summary_remains = 0;
            let summary_bonus = 0;
            let summary_official_salary = 0;
            let summary_categories = Object.keys(this.workersToShow).length
            let workers = Object.values(this.workers_info).filter(value => value.category.base_id == this.base_id)
            workers.map(worker => {
                if(worker.category.base_id == this.base_id){
                    summary_salary += this.salaryText(worker.id,true)
                    summary_factual_salary += this.factualSalaryText(worker.id,true)
                    summary_paid += this.alreadyPaidText(worker.id,true)
                    summary_bonus += this.paidBonusText(worker.id,true)
                    summary_official_salary += this.officialSalaryText(worker.id,true)
                }
            })
            summary_remains = summary_factual_salary - summary_paid;
            summary_salary = this.makeMoney(summary_salary,true)
            summary_paid = this.makeMoney(summary_paid,true)
            summary_remains = this.makeMoney(summary_remains,true)
            summary_factual_salary = this.makeMoney(summary_factual_salary,true)
            summary_bonus = this.makeMoney(summary_bonus,true)
            summary_official_salary = this.makeMoney(summary_official_salary,true)
            return"<td colspan='3'>Сотрудников(" + workers.length +")</td> " +
                "<td class='summary_salary'>"+ summary_salary +"</td> " +
                "<td class='summary_factual_salary'>"+ summary_factual_salary +"</td> " +
                "<td class='summary_paid'>"+ summary_paid +"</td> " +
                "<td class='summary_remains'>"+ summary_remains +"</td> " +
                "<td class='summary_bonus'>"+ summary_bonus +"</td> " +
                "<td class='summary_official_salary'>"+ summary_official_salary +"</td> " +
                "<td class=''></td> "

        },
        getAllSalaries(){
            let sum = 0;
            return "<p style='color:red'>asd</p>"
        },
        addNewWorker(){
            this.show_form = false
            this.show_user_form = true
        },
        resetMainDate(){

            this.MAIN_DATE = new Date()
        },
        oldHolidayStartDateChanged(ev,event_id){
            let worker = this.current_worker;
            worker.holidays.map(value => {
                if(value.id === event_id){

                    value.start_date = ev
                    value.modified = true
                }
            })

        },
        oldHolidayEndDateChanged(ev,holiday_id){
            let worker = this.current_worker;
            worker.holidays.map(value => {
                if(value.id === holiday_id){
                    value.end_date = ev
                    value.modified = true
                }
            })

        },
        oldSalaryChanged(ev,salary_id){
            ev.target.value = this.makeMoney(ev.target.value)
            let worker = this.current_worker;
            worker.salaries.map(value => {
                if(value.id === salary_id){
                    value.amount = ev.target.value ?  parseInt(ev.target.value.replace(/\D/g, '')) : 0
                    value.modified = true
                }
            })

        },
        oldBonusChanged(ev,salary_id){
            ev.target.value = this.makeMoney(ev.target.value)
            let worker = this.current_worker;
            worker.salaries.map(value => {
                if(value.id === salary_id){
                    value.bonus = ev.target.value ?  parseInt(ev.target.value.replace(/\D/g, '')) : 0
                    value.modified = true
                }
            })
        },
        oldIncreaseDateChanged(ev,event_id){
            let worker = this.current_worker;
            worker.increases.map(value => {
                if(value.id === event_id){
                    value.date = ev
                    value.modified = true
                }
            })
        },
        oldIncreaseAmountChanged(ev,event_id){
            let worker = this.current_worker;
            worker.increases.map(value => {
                if(value.id === event_id){
                    value.modified = true
                    value.new_value = ev.target.value ?  parseInt(ev.target.value.replace(/\D/g, '')) : 0
                }
            })
        },
        oldFiringDateChanged(ev,event_id){
            let worker = this.current_worker;
            worker.events.map(value => {
                if(value.id === event_id){
                    value.date = ev
                    value.modified = true
                }
            })
        },
        oldFiringCommentChanged(ev,event_id){
            let worker = this.current_worker;
            worker.events.map(value => {
                if(value.id === event_id){
                    value.additional.comment = ev.target.value
                    value.modified = true
                }
            })
        },
        dayOffLengthChanged(ev,event_id){
            let worker = this.current_worker;
            worker.holidays.map(value => {
                if(value.id === event_id){
                    value.modified = true
                }
            })
        },
        oldHolidayPaidChanged(ev,event_id){
            let worker = this.current_worker;
            worker.holidays.map(value => {
                if(value.id === event_id){
                    value.modified = true
                }
            })
        },
        deleteOldSalary(salary_id){
            let worker = this.current_worker;
            worker.salaries.map(value => {
                if(value.id === salary_id){
                    value.deleted_at = true
                    value.modified = true
                }
            })
        },
        restoreOldSalary(salary_id){
            let worker = this.current_worker;
            worker.salaries.map(value => {
                if(value.id === salary_id){
                    value.deleted_at = false
                    value.modified = true
                }
            })
        },
        /*updates all salary info when main date changed*/
        updateAllData(){
            this.form_disabled = true;
            axios.get('/axios/salary/get-select-bases/' + this.getCalculateYear, {
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).then(response => {

                this.base_id = null;
                this.category_id = null;
                this.worker_id = null;
                let select_bases = [];
                let hire_select_bases = [];
                this.bases = {};
                this.hire_bases = {};
                this.workers_info = {};
                this.table_workers = {};
                let worker_is_available = false;
                let category_is_available = false;
                let base_is_available = false;
                let hire_posts = [];
                Object.values(response.data.posts).map((post,index) => {
                    hire_posts.push({id:post.id, text:post.title})
                })
                this.posts_to_select = hire_posts;
                Object.values(response.data.bases).map((value,index) => {
                    let categories = {};
                    let category_options = [];
                    let hire_category_options = [];
                    Object.values(value.categories).map((category,category_index) => {
                        let worker_options = [];
                        let workers = [];

                        Object.values(category.still_workers).map((worker) => {
                            if(worker.id === parseInt(this.sticky_worker_id)){
                                worker_is_available = true;
                            }

                            worker_options.push({ id:worker.id, text: worker.name})
                            workers.push(worker)
                        })
                        hire_category_options.push({id:category.id, text: category.title})

                        if(workers.length){
                            if(category.id === parseInt(this.sticky_category_id)){
                                category_is_available = true;
                            }
                            category_options.push({id:category.id, text: category.title})
                            categories[category.id] = {
                                options:worker_options,
                                workers:workers,
                                category_name:category.title
                            }
                        }

                    })
                    hire_select_bases.push({ id:value.id, text: value.title})
                    this.hire_bases[value.id] = {
                        options:hire_category_options
                    }
                    if(category_options.length){
                        if(value.id === parseInt(this.sticky_base_id)){
                            base_is_available = true;
                        }
                        this.bases[value.id] = {
                            categories:categories,
                            options:category_options
                        }
                        select_bases.push({ id:value.id, text: value.title})
                    }

                })
                if(!base_is_available){
                    this.sticky_base_id = null;
                }
                if(!category_is_available){
                    this.sticky_category_id = null;
                }
                if(!worker_is_available){
                    this.sticky_worker_id = null;
                }
                Object.values(response.data.workers).map((worker) => {
                    this.workers_info[worker.id] = worker
                })
                if(!Object.values(this.workers_info).length){
                    this.show_form = false
                    this.show_user_form = true
                }
                this.form_disabled = false
                this.bases_to_select = select_bases;
                this.hire_bases_to_select = hire_select_bases;
                this.items_loaded =true
                this.$forceUpdate()
            });
        },
        /*opens main calendar when calendar icon clicked*/
        openMainDate(){
            this.$refs.statistic_date.showCalendar()
        },

        /*submits salary form*/
        submitSalary(){
            let check = [

            ]
            if(this.checkValidity(check)){
                let data = this.salaryExportData()
                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/salary/pay', data, config).then(response => {

                    let worker = response.data.worker;
                    this.workers_info[worker.id] = worker
                    this.worker_id = worker.id
                    this.form_disabled = false
                    this.official_salary_set = false
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top left',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });
                    this.salary = null
                    this.bonus = null
                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }else{
                this.form_disabled = false
            }
        },
        submitDayOff(){
            let check = [
            ]
            if(this.checkValidity(check) ){
                let data = this.dayOffExportData()
                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/salary/day-off', data, config).then(response => {

                    let worker = response.data.worker;
                    this.workers_info[worker.id] = worker
                    this.worker_id = worker.id
                    this.form_disabled = false
                    this.day_off = {
                        date:null,
                        length:null
                    }
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top left',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });

                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }else{
                this.form_disabled = false
            }
        },
        submitIncrease(){
            let check = [
            ]

            if(this.checkValidity(check) ){
                let data = this.increseExportData()
                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/salary/salary-increase', data, config).then(response => {

                    let worker = response.data.worker;
                    this.workers_info[worker.id] = worker
                    this.worker_id = worker.id
                    this.form_disabled = false
                    this.increase = {
                        date:null,
                        salary:null
                    }
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top left',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });

                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }else{
                this.form_disabled = false
            }
        },
        submitFiring(){
            let check = [
            ]

            if(this.checkValidity(check) ){
                let data = this.firingExportData()

                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/salary/firing', data, config).then(response => {

                    let worker = response.data.worker;
                    this.workers_info[worker.id] = worker
                    this.worker_id = worker.id
                    this.form_disabled = false
                    this.firing = {
                        date:null,
                        comment:null
                    }
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top left',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });

                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }else{
                this.form_disabled = false
            }
        },
        /*submits extra holiday form*/
        submitHoliday(){
            let check = [
            ]
            if(this.checkValidity(check)){
                let data = this.holidayExportData()

                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/salary/holiday', data, config).then(response => {

                    let worker = response.data.worker;
                    this.workers_info[worker.id] = worker
                    this.worker_id = worker.id
                    this.form_disabled = false
                    this.holiday_date = {
                        from:null,
                        to:null
                    }
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top left',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });

                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }else{
                this.form_disabled = false
            }
        },
        /*submit button clicked*/
        submitForm(){
            this.form_disabled = true;
            switch (this.event_id){
                case 'salary':
                    this.submitSalary();
                    break;
                case 'holiday':
                    this.submitHoliday()
                    break;
                case 'day_off':
                    this.submitDayOff()
                    break;
                case 'increase':
                    this.submitIncrease()
                    break;
                case 'firing':
                    this.submitFiring()
                    break;
            }
        },
        submitHireForm(){
            let data = this.hireExportData()
            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/add-worker', data, config).then(response => {
                let worker = response.data.worker;
                this.workers_info[worker.id] = worker
                this.form_disabled = false
                this.hire_worker.name = null
                this.hire_worker.post = null
                this.hire_worker.salary = null
                this.hire_worker.official_salary = null
                this.setEditWorker(worker.id,worker.category_id)
                this.updateAllData()
            }).catch(e => {
                this.form_disabled = false;
                console.log(e);
            });
        },
        tablePages(){
            let buttons = [];
            let length = this.table_workers.length;
            if(length > this.workers_per_page){
                for(let i = 0; i < Math.ceil(this.table_workers.length/this.workers_per_page);i++){
                    buttons.push(i+1)
                }
            }
            return buttons
        },
        setTablePage(index){
            this.table_current_page = index + 1
        },
        dayOffExportData(){
            return{
                worker_id:this.worker_id,
                date:this.day_off.date,
                main_date:this.getCalculateYear,
                days:this.day_off.length,
                holidays:this.current_worker.holidays,
            }
        },
        increseExportData(){
            return{
                worker_id:this.worker_id,
                date:this.increase.date,
                main_date:this.getCalculateYear,
                salary:this.increase.salary ? this.increase.salary.replace(/\D/g, '') : 0,
                increases:this.current_worker.increases,
            }
        },
        salaryExportData(){

            return{
                worker_id:this.worker_id,
                salary:this.salary ? this.salary.replace(/\D/g, ''): 0,
                date:this.date,
                official:this.official_salary_set,
                bonus:this.bonus ?this.bonus.replace(/\D/g, '') : 0,
                main_date:this.getCalculateYear,
                salaries:this.current_worker.salaries
            }
        },
        hireExportData(){
            return{
                salary:this.hire_worker.salary ? this.hire_worker.salary.replace(/\D/g, ''): 0,
                official_salary:this.hire_worker.official_salary ? this.hire_worker.official_salary.replace(/\D/g, ''): 0,
                date:this.hire_worker.date,
                main_date:this.getCalculateYear,
                additional:this.hire_worker.additional,
                post_id:this.hire_worker.post_id,
                name:this.hire_worker.name,
                base_id:this.hire_base_id,
                category_id:this.hire_category_id
            }
        },
        holidayExportData(){
            return{
                worker_id:this.worker_id,
                from:this.holiday_date.from,
                to:this.holiday_date.to,
                holidays:this.current_worker.holidays,
                main_date:this.getCalculateYear
            }
        },
        firingExportData(){
            return{
                worker_id:this.worker_id,
                date:this.firing.date,
                comment:this.firing.comment,
                main_date:this.getCalculateYear,
                events:this.current_worker.events
            }
        },
        hireSalaryChanged(ev){
            this.hire_worker.salary = this.makeMoney(ev.target.value)
        },
        officialSalaryChanged(ev){
            this.hire_worker.official_salary = this.makeMoney(ev.target.value)
        },
        increaseChanged(ev){
            this.increase.salary = this.makeMoney(ev.target.value)
        },
        salaryChanged(ev){
            this.salary = this.makeMoney(ev.target.value)
        },
        bonusChanged(ev){
            this.bonus = this.makeMoney(ev.target.value)
        },
        salaryKeyDown(e){

        },
        checkValidity(values_to_check){
            for(let i = 0; i < values_to_check.length; i++){
                if(!values_to_check[i].value){
                    this.requiredAlert(values_to_check[i].ref,values_to_check[i].title)
                    return false;
                }
            }
            return true
        },
        workerTableColor(worker_id){
            let classname = '';
            let worker = this.workers_info[worker_id];
            let already_paid = this.alreadyPaidText(worker_id,true)
            let salary = this.factualSalaryText(worker_id,true)
            if( already_paid > salary){
                classname =  'too_much'
            }else if(already_paid === salary){
                classname =  'ok'
            }else if(already_paid >= salary/2){
                classname =  'half'
            }else if(already_paid > 0){
                classname =  'some'
            }
            if(this.officialPaid(worker_id)){
                classname += ' official_paid'
            }
            if(worker.official_salary){
                classname += ' has_official_salary'
            }
            return classname;
        },
        showProgressTooltips(){
            this.progress_active = true;
            if(this.progress_show_timeout){
                clearTimeout(this.progress_show_timeout)
            }
            this.progress_show_timeout = setTimeout(()=>{
                this.progress_active = false;
            },5000)
        },
        setEditWorker(worker_id, cat_id){
            console.log('setEditWorker')
            localStorage.setItem('worker_id',worker_id);
            this.force_worker_already_set = false;
            this.show_user_form = false;
            this.show_form = true;
            this.sticky_category_id = cat_id;
            this.sticky_base_id = this.base_id;
            this.sticky_worker_id = worker_id;
            this.worker_id = worker_id;
            this.date = new Date(this.MAIN_DATE);
            if(this.event_id === 'salary'){
                this.$nextTick(function (){
                    this.$refs.salary_input.focus()
                })
            }
            this.showProgressTooltips()
        },
        rotateCategoryClass(length){
            if(length >= 10){
                return 'big'
            }else if(length > 4){
                return 'medium'
            }else{
                return 'small'
            }
        },
        hide_form(){
            this.show_form = false
        },

        hide_worker_form(){
            this.show_user_form = false
        },
        setEvent(event){
            this.event_id = event
        },
        makeMoney(value, with_sign = false) {
            value += ''
            let minus = false;
            if (value[0] === '-') {
                minus = true;
            }
            value = value.replace(/\D/g, '')

            if (value[0] === '0') {
                value = value.substr(1, value.length);
            }
            let result = '';
            let check = true;
            while (check) {
                if (value.length > 3) {
                    result = ', ' + value.substr(value.length - 3, 3) + result;
                    value = value.substr(0, value.length - 3)
                } else {
                    result = value + result;
                    check = false;
                }
            }
            if(with_sign){
                if(result.length){
                    result += ' ₽'
                }else{
                    result = '0 ₽'
                }
            }
            if(minus){
                result = '- ' + result
            }


            return result;
        },
        requiredAlert(ref,name){

            if(ref === 'date_input'){
                this.$refs[ref].isOpen ||  this.$refs[ref].showCalendar()
            }else{
                this.$refs[ref].focus()
            }
            Lobibox.notify('info', {
                title:'Внимание',
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top left',
                icon: 'bx bx-info-circle',
                msg: 'поле ' + name + ' обязателно к заполнению'
            });
        },
        /*workers salary info*/
        salaryText(worker_id = null,type_number = false){
            let salary = 0;
            if(worker_id || this.worker_id){
                let worker = worker_id ? this.workers_info[worker_id] : this.current_worker;
                if(worker){
                    salary = worker.last_increase.new_value
                }

            }
            if(type_number){
                return salary
            }
            return this.makeMoney(salary,true);
        },
        /*workers clean salary info*/
        factualSalaryText:function (worker_id = null,type_number = false){
            let factual_salary = 0
            if(worker_id || this.worker_id){
                let worker = worker_id ? this.workers_info[worker_id] : this.current_worker;
                let salary = this.salaryText(worker_id,true)
                let daysBeforeStart = this.startOfWork(worker_id);
                let extraHolidays = this.extraHolidayText(worker_id);
                let unpaidDayOffs = this.dayOffText(worker_id);
                let month_length = 30;
                let none_worked_days = extraHolidays + daysBeforeStart + unpaidDayOffs;
                let withhold = Math.ceil(( none_worked_days / month_length) * salary);
                factual_salary  = salary - withhold;
            }
            if(type_number){
                return factual_salary;
            }
            return this.makeMoney(factual_salary,true)

        },
        officialSalaryText:function (worker_id = null,type_number = false){
            let official_salary = 0
            if(worker_id || this.worker_id){
                let worker = worker_id ? this.workers_info[worker_id] : this.current_worker;
                official_salary = worker.official_salary
            }
            if(type_number){
                return official_salary;
            }
            return this.makeMoney(official_salary,true)

        },
        startOfWork(worker_id = null,return_date = false){
            let start = 0;
            let start_date = null;
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                if(worker_obj){
                    let event = worker_obj.events.find(value => value.type_id === 1)
                    if(event){
                        start = new Date(event.date).getDate() - 1
                        start_date = this.dayAndMonthAndYear(event.date);
                    }
                }
            }
            if(return_date){
                return start_date
            }
            return start
        },
        /*workers already paid  salary info*/
        alreadyPaidText(worker_id = null,type_number = false){
            let paid = 0;
            if(this.worker_id || worker_id){

                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                if(worker_obj){
                    worker_obj.salaries.map(value => {
                        if(!value.deleted_at){
                            paid += value.amount
                        }

                    })
                }


            }
            if(type_number){
                return paid
            }
            return this.makeMoney(paid,true)
        },
        payTillHalf(){
            return Math.ceil(this.factualSalaryText(null,true)/2) - this.alreadyPaidText(null,true) > 0
        },

        paidMoreThanNeeded(worker_id = null){
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                return  this.factualSalaryText(worker_id,true) < this.alreadyPaidText(worker_id,true)
            }
        },

        /*remainder of workers salary*/
        salaryRemainderText(worker_id = null,type_number = false){
            let remainder = 0;
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                remainder = this.factualSalaryText(worker_id,true) - this.alreadyPaidText(worker_id,true)
            }
            if(type_number){
                return remainder
            }
            return this.makeMoney(remainder, true)
        },
        salaryRemainderHalf(worker_id){
            return this.makeMoney(Math.floor(this.salaryRemainderText(worker_id,true) - (this.factualSalaryText(worker_id,true)/2)),true)
        },
        autoFillRemainder(half = false){
            let remainder = this.salaryRemainderText(null,true)
            let salary = this.factualSalaryText(null,true)
            if(remainder > 0){
                if(half){
                    this.salary = this.makeMoney(Math.floor(remainder - (salary/2)));
                }else{
                    this.salary = this.makeMoney(remainder);
                }

            }
        },
        /*workers paid bonus info*/
        paidBonusText(worker_id = null, type_number = false){
            let bonus = 0;
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                if(worker_obj){
                    worker_obj.salaries.map(value => {
                        bonus += value.bonus ?? 0
                    })
                }


            }
            if(type_number){
                return bonus
            }
            return this.makeMoney(bonus,true)
        },
        /*workers axtra holidays info*/
        extraHolidayText(worker_id = null,all_needed = false){
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                let miss_days = 0;
                if(worker_obj){
                    worker_obj.holidays.map(value => {
                        if(value.end_date && value.type === 'holiday'){
                            if(all_needed || !value.paid){
                                let start = new Date(value.start_date)
                                let end = new Date(value.end_date)
                                if(end > start) {
                                    if(start.getMonth() !== this.MAIN_DATE.getMonth()){
                                        miss_days += end.getDate() - 1;
                                    }else if(end.getMonth() !== this.MAIN_DATE.getMonth()){
                                        miss_days += this.daysInMonth(this.MAIN_DATE.getMonth() + 1,this.MAIN_DATE.getFullYear()) - start.getDate() + 1;
                                    }else{
                                        miss_days += end.getDate() - start.getDate()
                                    }
                                }

                            }
                        }
                    })
                }
                return miss_days;
            }
            return 0;
        },

        dayOffText(worker_id = null,all_needed = false){
            if(this.worker_id || worker_id){
                let worker_obj = worker_id ? this.workers_info[worker_id] : this.current_worker;
                let miss_days = 0;
                if(worker_obj){
                    worker_obj.holidays.map(value => {
                        if(value.type === 'day_off'){
                            if(all_needed || !value.paid){
                                miss_days += parseInt(value.additional.days)
                            }
                        }
                    })
                }
                return miss_days;
            }
            return 0;
        },

        fullDigitMonth(month){
            month += '';
            if(month.length === 1){
                month = '0' + month
            }
            return month
        },
        dayAndMonth(initial_date, sign = null){
            sign = sign ?? '/'
            let date = new Date(initial_date);
            let month = date.getMonth()
            let day = date.getDate()
            return day + sign + this.fullDigitMonth(month+1)
        },
        dayAndMonthAndYear(initial_date){
            let date = new Date(initial_date);
            let year = date.getFullYear()
            let month = date.getMonth()
            let day = date.getDate()
            return this.fullDigitMonth(day) + '/' + this.fullDigitMonth(month+1) + '/' + year
        },
        salariesList(){
            let salaries = [];
            if(this.worker_id){
                let worker_obj = this.current_worker;
                if(worker_obj){
                    worker_obj.salaries.map(value => {
                        if(!value.type){
                            if(value.amount && value.bonus){
                                value.type = 'both'
                            }else if(value.amount){
                                value.type = 'salary'
                            }else{
                                value.type = 'bonus'
                            }
                        }
                        if(value.type === 'salary' || value.type === 'both'){
                            salaries.push({
                                id:value.id,
                                amount:this.makeMoney(value.amount),
                                date:this.dayAndMonth(value.for,'.'),
                                deleted:value.deleted_at,
                                official:value.additional && value.additional.official_salary
                            })
                        }


                    })
                }

            }
            this.$nextTick(() => {
                this.focusInitialSalary()
            })
            return salaries;
        },
        tooltipList(){
            let salaries = {};
            if(this.worker_id){
                let worker_obj = this.current_worker;
                if(worker_obj){
                    worker_obj.salaries.filter(salary => !salary.deleted_at).map(value => {
                        if(!value.deleted_at){
                            let value_date = new Date(value.for)
                            let day = value_date.getDate()
                            if(salaries[day]){
                                salaries[day].amount += value.amount
                            }else{

                                salaries[day] = {
                                    amount:value.amount,
                                    date:this.dayAndMonth(value.for,'.'),
                                    id:value.id,
                                    left:Math.floor((day/this.daysInMonth(value_date.getMonth()+1,value_date.getFullYear()))*100),
                                    offset_left:0,
                                    can_move:false
                                }
                            }
                        }
                    })

                        Object.values(salaries).map((value, index, array) => {
                            value.amount = this.makeMoney(value.amount, true);
                            if(Object.values(salaries).length > 1) {
                                if (index === 0) {
                                    value.offset_left = value.left
                                }
                                else {
                                    value.offset_left = value.left - array[index - 1].left
                                }

                                if(index !== array.length-1){
                                    if(array[index + 1].left - value.left < 10){
                                        let check = this.moveTooltip(array,index, 10 - array[index + 1].left + value.left)
                                        array = check.array

                                    }
                                }
                            }
                        })
                }


            }
            return salaries;
        },
        moveTooltip(array,index,offset){
            let item = array[index];
            if(index === 0){
                item.can_move = item.offset_left > 10 + offset;
                if(item.can_move){
                    item.left -= offset;
                    item.offset_left -= offset;
                    array[index+1].offset_left += offset

                    return {can_move:true, array:array, step:offset}
                }else{

                    return {can_move:false}
                }
            }
            else{
                item.can_move = item.offset_left > 10 + offset;
                if(item.can_move){
                    item.left -= offset;
                    item.offset_left -= offset;
                    array[index+1].offset_left += offset

                    return {can_move:true, array:array, step:offset}
                }else{
                    let check = this.moveTooltip(array,index - 1, offset);
                    if(check.can_move){
                        array = check.array
                        item.left -= check.step;
                        item.offset_left -= check.step;
                        array[index+1].offset_left += check.step

                        return {can_move:true, array:array, step:check.step}
                    }else{

                        return {can_move:false}
                    }
                }
            }
        },
        bonusesList(){
            let salaries = [];
            if(this.worker_id){
                let worker_obj = this.current_worker;
                if(worker_obj){
                    worker_obj.salaries.map(value => {
                        if(!value.type){
                            if(value.amount && value.bonus){
                                value.type = 'both'
                            }else if(value.amount){
                                value.type = 'salary'
                            }else{
                                value.type = 'bonus'
                            }
                        }
                        if(value.type === 'bonus' || value.type === 'both'){
                            salaries.push({
                                id:value.id,
                                amount:this.makeMoney(value.bonus),
                                date:this.dayAndMonth(value.for),
                                deleted:value.deleted_at
                            })
                        }


                    })
                }

            }
            return salaries;
        },
        holidaysList(){
            let holidays = [];
            if(this.worker_id){
                let worker_obj = this.current_worker;
                worker_obj.holidays.map(value => {
                    if(value.type === 'holiday'){

                        holidays.push(value)
                    }

                })
            }
            return holidays;
        },
        dayOffList(){
            let days_off = [];
            if(this.worker_id){

                let worker_obj = this.current_worker;
                worker_obj.holidays.map(value => {
                    if(value.type === 'day_off'){
                        days_off.push(value)
                    }
                })
            }
            return days_off;
        },
        increasesList(){
            let increases = [];
            if(this.worker_id){
                let worker_obj = this.current_worker;
                worker_obj.increases.map(value => {
                    increases.push({
                        id:value.id,
                        salary:this.makeMoney(value.new_value),
                        date:value.date,
                    })
                })
            }
            return increases;
        },
        firingList(){
            let firings = [];
            if(this.worker_id){
                let worker_obj = this.current_worker;
                worker_obj.events.map(value => {
                    if(value.type_id === 2){
                        firings.push({
                            id:value.id,
                            date:value.date,
                            additional:value.additional.comment
                        })
                    }
                })
            }
            return firings;
        },




    },
}
</script>

<style scoped>

</style>
