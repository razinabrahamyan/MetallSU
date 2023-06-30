<template>
    <div>
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 index_header_handler">
            <div class="breadcrumb-title pe-3">Сотрудник</div>
        </div>
        <div v-show="!items_loaded">
            <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
        </div>
        <div v-show="items_loaded" class="worker_homepage_tab">
            <!--            modal for firing a worker                                  -->
            <div id="fire_worker_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Уволить Сотрудника</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#fire_worker_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <div>
                                    <label for="firing_date" class="form-label">Дата Уволнения</label>
                                    <datepicker
                                        :disabledDates="{ to: getAvailableReEmployDate }"
                                        :id="'firing_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="firing.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label for="firing_comment" class="form-label" >Комментарий</label>
                                    <textarea  v-model="firing.comment" id="firing_comment" class="form-control"></textarea>
                                </div>

                                <div class="mt-4">
                                    <button  class="btn btn-light" :disabled="form_disabled" @click="submitFiring()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for increasing a workers salary                   -->
            <div id="increase_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Прибавка Зарплаты</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#increase_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div>
                                    <label for="worker_increase_date" class="form-label">Дата Прибавки</label>
                                    <datepicker

                                        :id="'worker_increase_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="increase.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label for="worker_increase_salary" class="form-label" >Новая Зарплата</label>
                                    <input type="text" @keyup="increaseChanged"  v-model="increase.salary" id="worker_increase_salary" class="form-control">
                                </div>

                                <div class="mt-4">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitIncrease()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for changing a workers category                          -->
            <div id="category_change_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Смена Отдела</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#category_change_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div>
                                    <label for="worker_category_change_date" class="form-label">Дата Смены</label>
                                    <datepicker
                                        :id="'category_change'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="category_change.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" >Новый Отдел</label>
                                    <select2-component id="worker_new_category" :force_value="category_change.force" :options="categories" v-model="category_change.category"></select2-component>
                                </div>

                                <div class="mt-4">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitCategoryChange()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for re employing a worker                          -->
            <div id="re_employ_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Восстановить Сотрудника</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#re_employ_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div>
                                    <label for="re_employ_date" class="form-label">Дата Восстановления</label>
                                    <datepicker
                                        :disabledDates="{ to: getAvailableReEmployDate }"
                                        :id="'re_employ_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="re_employ.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label for="firing_comment" class="form-label" >Комментарий</label>
                                    <textarea  v-model="re_employ.comment" id="re_employ_comment" class="form-control"></textarea>
                                </div>

                                <div class="mt-4">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitReEmploy()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for editing workers firing event                          -->
            <div id="edit_fire_worker_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Редактировать Уволнение</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#edit_fire_worker_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <div>
                                    <label for="edit_firing_date" class="form-label">Дата Уволнения</label>
                                    <datepicker
                                        :id="'edit_firing_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="edit_firing.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label for="edit_firing_comment" class="form-label" >Комментарий</label>
                                    <textarea  v-model="edit_firing.comment" id="edit_firing_comment" class="form-control"></textarea>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitEditFiring()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                    <button class="btn btn-light text-karmir" @click="submitFiringDelete(edit_firing.id)" :disabled="form_disabled">Удалить <i class="bx bx-trash m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for editing workers salary increase event              -->
            <div id="edit_increase_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Редактировать Прибавку</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#edit_increase_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div v-if="!edit_increase.initial">
                                    <label for="edit_worker_increase_date" class="form-label">Дата Прибавки</label>
                                    <datepicker

                                        :id="'edit_worker_increase_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="edit_increase.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label v-if="edit_increase.initial" for="edit_worker_increase_salary" class="form-label" >Зарплата</label>
                                    <label v-else for="edit_worker_increase_salary" class="form-label" >Новая Зарплата</label>
                                    <input type="text" @keyup="editIncreaseChanged"  v-model="edit_increase.salary" id="edit_worker_increase_salary" class="form-control">
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitEditIncrease()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                    <button v-if="!edit_increase.initial" class="btn btn-light text-karmir" @click="submitIncreaseDelete(edit_increase.id)" :disabled="form_disabled">Удалить <i class="bx bx-trash m-0"> </i></button>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for editing workers category change event                     -->
            <div id="edit_category_change_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Редактирование Смены Отдела</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#edit_category_change_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div>
                                    <label for="edit_worker_category_change_date" class="form-label">Дата Смены</label>
                                    <datepicker
                                        :id="'edit_worker_category_change_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="edit_category_change.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label for="edit_worker_new_category" class="form-label" >Новый Отдел</label>
                                    <select2-component id="edit_worker_new_category" :force_value="edit_category_change.force" :options="categories" v-model="edit_category_change.category"></select2-component>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <button :disabled="form_disabled" class="btn btn-light" @click="submitEditCategoryChange()">Сохранить <i class="bx bx-save m-0"> </i></button>
                                    <button class="btn btn-light text-karmir" @click="submitCategoryChangeDelete(edit_category_change.id)" :disabled="form_disabled">Удалить <i class="bx bx-trash m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--            modal for editing workers re employ event                -->
            <div id="edit_re_employ_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Редактирование Принятия на работу</h5>
                            <button type="button" class="close p-1" data-dismiss="modal" aria-label="Close"
                                    onclick="$('#edit_re_employ_modal').modal('hide');">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">

                                <div>
                                    <label :for="'edit_re_employ_date'" class="form-label">Дата Принятия</label>
                                    <datepicker
                                        :id="'edit_re_employ_date'"
                                        ref="date_input"
                                        :input-class="'form-control'"
                                        :placeholder="'Выберите дату'"
                                        :language="language"
                                        :monday-first="true"
                                        v-model="edit_re_employ.date">

                                    </datepicker>
                                </div>
                                <div class="mt-2">
                                    <label :for="'edit_re_employ_comment'" class="form-label" >Комментарий</label>
                                    <textarea  v-model="edit_re_employ.comment" id="edit_re_employ_comment" class="form-control"></textarea>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <button class="btn btn-light" @click="submitEditReEmploy()" :disabled="form_disabled">Сохранить <i class="bx bx-save m-0"> </i></button>
                                    <button v-if="edit_re_employ.type === 're_employ'" class="btn btn-light text-karmir" @click="submitReEmployDelete(edit_re_employ.id)" :disabled="form_disabled">Удалить <i class="bx bx-trash m-0"> </i></button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="text-white">
                                    <i :class="['bx bxs-user fs-1',{'text-karmir':current_worker_all.last_event.type_id === 2,'text_kanach':current_worker_all.last_event.type_id === 1}]"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0 workers_name_fix" ref="workers_name_span">{{current_worker.name}}</h4>
                                    <div class="homepage_header_comment" :style="'min-width:' + workerNameWidth + 'px'">
                                        <span><small v-if="current_worker.additional" class="mb-0">{{current_worker.additional.slice(0,50)}}</small></span>
                                        <span v-if="current_worker.additional && current_worker.additional.length > 50">...</span>
                                    </div>


                                </div>
                            </div>
                            <div v-if="current_worker_all.last_event.type_id === 2">
                                <div class="d-flex fs-6">
                                    <i class="bx bx-message-error text-karmir"></i>
                                    <p class="m-0 text-karmir ms-2">Уволен с <span class="text-white text-decoration-underline">{{convertDateToShow(current_worker_all.last_event.date)}}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="ms-3 border-left align-self-baseline worker_homepage_top_info">
                            <div v-if="current_worker.post">
                                <div class="d-flex fs-6">
                                    <i class="bx bxs-briefcase-alt-2 text_date"></i>
                                    <p class="m-0 text-white ms-2">{{current_worker.post.title}}</p>
                                </div>
                            </div>
                            <div v-if="current_worker.last_increase.new_value">
                                <div class="d-flex fs-6">
                                    <i class="bx bx-ruble text_kanach"></i>
                                    <p class="m-0 text-white ms-2">{{makeMoney(current_worker.last_increase.new_value,true)}}</p>
                                </div>
                            </div>
                            <div v-if="current_worker.official_salary">
                                <div class="d-flex fs-6">
                                    <i class="bx bx-book-open text-day-off"></i>
                                    <p class="m-0 text-white ms-2">{{makeMoney(current_worker.official_salary, true)}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto text-white fs-1"><div>
                            <button @click="toSalariesPage()" class="btn btn-light">Зарплаты <i class="bx bx-ruble"></i></button>
                        </div></div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-4 border-end">
                        <div class="card-body">
                            <ul class="nav nav-tabs " role="tablist">
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#current" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-calendar-week font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">За этот месяц</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#all" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-calendar font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">За все время</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#profile" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-user font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Профиль</div>
                                        </div>
                                    </a>
                                </li>


                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" id="current" role="tabpanel">
                                    <span>Выплачено</span>
                                    <h5 class="mb-3">{{alreadyPaidText() + '/' + factualSalaryText()}}</h5>
                                    <div :class="['position-relative salary_progress has_tooltips mt-2']">
                                        <div class="progress" style="height:7px;">
                                            <div :class="['progress-bar progress-bar-striped progress-bar-animated', progressBar.background]" role="progressbar" :style="{width:progressBar.percent + '%'}" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div v-for="(tooltip,name,index) in tooltipList()" :class="['salary_tooltip',{'too_right':tooltip.left > 95 || (tooltip.left > 85 && index !== tooltipList.length-1)}]" :style="'left:' + tooltip.left + '%'" >
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

                                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 row-group mt-2 current_month_info">
                                        <div class="col mb-2">
                                            <div class="card mb-0 h-100" @click="setActiveTab('value')">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <h5 class="mb-0 ">{{salaryText()}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-ruble text-white fs-3"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center text-white">
                                                        <p class="mb-0 card_label">Оклад</p>
                                                        <!--                                                        <p class="mb-0 ms-auto">+4.2%<span><i class="bx bx-up-arrow-alt"></i></span></p>-->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col mb-2">
                                            <div class="card mb-0 h-100">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between ">
                                                    <div class="d-flex align-items-center">
                                                        <h5 class="mb-0 ">{{factualSalaryText()}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-ruble text-white fs-3"></i>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center text-white">
                                                        <p class="mb-0 card_label text_salary">Зарплата</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col mb-1">
                                            <div class="card mb-0 h-100">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between ">
                                                    <div class="d-flex align-items-center">
                                                        <h5 :class="['mb-0 paid',progressBar.background]">{{alreadyPaidText()}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-ruble text-white fs-3"></i>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-1" style="height:3px;">
                                                        <div class="progress-bar" role="progressbar" :style="'width:' + progressBar.percent + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="d-flex align-items-center text-white">
                                                        <p :class="['mb-0 card_label paid',progressBar.background]">Выплачено</p>
                                                        <p class="mb-0 ms-auto"><span v-if="progressBar.background === 'too_much'" class="text-danger"> <i class="bx bx-right-top-arrow-circle"></i></span>{{progressBar.percent}}%</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col mb-1">
                                            <div class="card mb-0 h-100">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between ">
                                                    <div class="d-flex align-items-center">
                                                        <h5 :class="['mb-0 remains',progressBar.background]">{{salaryRemainderText()}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-ruble text-white fs-3"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center text-white">
                                                        <p class="mb-0 card_label text_remains">Остаток</p>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col mb-1">
                                            <div class="card mb-0 h-100">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between ">
                                                    <div class="d-flex align-items-center">
                                                        <h5 class="mb-0 text-holiday">{{extraHolidayText(true)}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-calendar text-white fs-3"></i>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center text-white">
                                                        <p class="mb-0 card_label">Выходной</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col mb-1">
                                            <div class="card mb-0 h-100">
                                                <div class="card-body py-2 d-flex flex-column justify-content-between ">
                                                    <div class="d-flex align-items-center">
                                                        <h5 :class="['mb-0',{'text-holiday':dayOffText(true)}]">{{dayOffText(true)}}</h5>
                                                        <div class="ms-auto">
                                                            <i class="bx bx-calendar text-white fs-3"></i>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center text-white">
                                                        <p class="mb-0 card_label">Отпуск</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="all" role="tabpanel">
                                    <span>Выплачено</span>
                                    <h5 class="mb-3">{{alreadyPaidText(false,true)}}</h5>
                                    <span>Отпусков</span>
                                    <h5 class="mb-3">{{extraHolidayText(true,true)}}</h5>
                                    <span>Выходных</span>
                                    <h5 class="mb-3">{{dayOffText(true,true)}}</h5>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="d-flex justify-content-end mt-0">
                                                <div>
                                                    <div class="ms-auto">
                                                        <div class="btn-group">
                                                            <button type="button" class="button_no_nothing text-white fs-6" data-bs-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings text-white"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></button>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" style="margin: 0px;">
                                                                <a class="dropdown-item" onclick="$('#increase_modal').modal('show');" role="button">Прибавка зарплаты <i class="bx bx-ruble text-white"></i></a>
                                                                <a class="dropdown-item" onclick="$('#category_change_modal').modal('show');" role="button">Сменить Отдел <i class="bx bx-transfer text_kanach"></i></a>
                                                                <div class="dropdown-divider"></div>
                                                                <a v-if="current_worker_all.last_event.type_id === 1" onclick="$('#fire_worker_modal').modal('show');" role="button" class="dropdown-item">Уволить Сотрудника <i class="bx bx-briefcase text-karmir"></i></a>
                                                                <a v-else onclick="$('#re_employ_modal').modal('show');" role="button" class="dropdown-item">Восстановить Сотрудника <i class="bx bx-briefcase text_kanach"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Имя</label>
                                                <input type="text" v-model="current_worker.name" class="form-control" id="name">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="post" class="form-label">Должность</label>
                                                <select id="post" class="form-select" v-model="current_worker.post_id">
                                                    <option value="">Выберитье должность</option>
                                                    <option v-for="post in  posts" :value="post.id">{{post.title}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="salary" class="form-label">Зарплата</label>
                                                <input type="text" @keyup="salaryChanged" v-model="salary" class="form-control" id="salary">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="official_salary" class="form-label">Официальная Зарплата</label>
                                                <input type="text" @keyup="officialSalaryChanged" v-model="official_salary" class="form-control" id="official_salary">
                                            </div>
                                            <div class="col-12">
                                                <label for="additional" class="form-label">Дополнительно</label>
                                                <textarea v-model="current_worker.additional" class="form-control" id="additional" placeholder="Address..." rows="3"></textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Отдел</label>
                                                <div class="d-flex ">
                                                    <p class="m-0 fs-6 text-white">{{current_worker.category.base.title + '/' + current_worker.category.title}}</p>
                                                    <button class="ms-2 text-white button_no_nothing text-decoration-underline" onclick="$('#category_change_modal').modal('show')">Изменить</button>
                                                </div>

                                            </div>
                                            <div class="col-12">
                                                <button @click="submitProfileForm" :disabled="form_disabled" type="submit" class="btn btn-light">Сохранить <i class="bx bx-save m-0"></i></button>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <ul class="nav nav-tabs " role="tablist">
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#history" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-history font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">История</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#salaries" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-info-square font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Зарплаты</div>
                                        </div>
                                    </a>
                                </li>


                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="history" role="tabpanel">
                                    <div class="pb-3 worker_history_handler">
                                        <div class="history_scroll">
                                            <div class="history_config_buttons_handler">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex align-items-center gap-3 ps-2 py-2">
                                                        <div @click="historyTypeSelected('all')"
                                                             title="Показать Все"
                                                             :class="['config_type type_all bg-kanach',{'active':history_config.show_all}]" >
                                                            <i class="fs-5 bx bx-refresh text-white"></i>
                                                        </div>
                                                        <div @click="historyTypeSelected(config.type)"
                                                             :title="config.title"
                                                             :class="['config_type',{'active':config.selected || history_config.show_all}]"
                                                             v-for="config in history_config.types">
                                                            <i :class="[config.class,'fs-5']"></i>
                                                        </div>
                                                        <div v-if="!history_config.show_all" title="Сбросить фильтры" @click="resetHistorySelection()" class="reset_history_selection">
                                                            <i class="bx bx-x fs-5"></i>
                                                        </div>
                                                    </div>
                                                    <div class="pe-2 py-2">
                                                        <div title="Сортиоровать по дате" @click="sortHistoryAscDesc()" :class="['sort_desc',{'active':history_config.sort_desc}]" >
                                                            <i v-if="history_config.sort_desc" class="bx bx-sort-up fs-5"></i>
                                                            <i v-else class="bx bx-sort-down fs-5"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <template v-if="selectedHistory.length">
                                                <div v-for="(event, index) in selectedHistory">
                                                    <div class="row m-0 worker_history_card">
                                                        <div class="col-auto p-0 text-center flex-column d-none d-sm-flex">
                                                            <div class="row h-50 none_selectable">
                                                                <div :class="['col',{'border-end':index !== 0}]">&nbsp;</div>
                                                                <div class="col">&nbsp;</div>
                                                            </div>
                                                            <h5 class="m-2">
                                                    <span class="badge rounded-pill bg-light border">
                                                        <template v-if="event.history_type === 'event'">
                                                            <template v-if="event.type_id === 1">
                                                                <i class="bx bx-briefcase fs-5"></i>
                                                            </template>
                                                            <template v-else-if="event.type_id === 2">
                                                                <i class="bx bx-briefcase-alt-2 text-karmir fs-5"></i>
                                                            </template>
                                                            <template v-else-if="event.type_id === 3">
                                                                <i class="bx bx-transfer text_kanach fs-5"></i>
                                                            </template>
                                                        </template>
                                                        <template v-else-if="event.history_type === 'increase'">
                                                            <i class="bx text-blue bx-ruble fs-5"></i>
                                                        </template>
                                                        <template v-else-if="event.history_type === 'holiday'">
                                                           <i class="bx bx-world text-holiday fs-5"></i>
                                                        </template>
                                                         <template v-else-if="event.history_type === 'day_off'">
                                                          <i class="bx bx-calendar-event text-day-off fs-5"></i>
                                                        </template>

                                                    </span>
                                                            </h5>
                                                            <div class="row h-50">
                                                                <div :class="['col',{'border-end':index !== history.length -1}]">&nbsp;</div>
                                                                <div class="col">&nbsp;</div>
                                                            </div>
                                                        </div>
                                                        <div class="col py-2">
                                                            <div class="card  mb-0">
                                                                <div class="card-body py-2">
                                                                    <template v-if="event.history_type === 'event'">
                                                                        <template v-if="event.type_id === 1">
                                                                            <template v-if="event.additional.type === 'hiring'">
                                                                                <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                                <h6 class="card-title mb-1 text-white">Принятие на работу</h6>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="card-text m-0">
                                                                                        <small class="">зарплата: </small><span class="text-white">{{makeMoney(current_worker.salary,true)}}</span>
                                                                                        <small class="">комм: </small><span class="text-white">{{event.additional.comment}}</span>
                                                                                    </p>
                                                                                    <button class="btn fs-6 history_edit btn-light p-1 py-0" @click="editReEmployEvent(event)"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                                </div>

                                                                            </template>
                                                                            <template v-else-if="event.additional.type === 're_employ'">
                                                                                <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                                <h6 class="card-title mb-1 text-white">Восстановление на работе</h6>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="card-text m-0"><small class="">комм: </small><span class="text-white">{{event.additional.comment}}</span></p>
                                                                                    <div class="d-flex">
                                                                                        <button class="btn fs-6 history_edit btn-light p-1 py-0" @click="editReEmployEvent(event)"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                                        <button class="btn ms-2 fs-6 text-karmir btn-light p-1 py-0" @click="submitReEmployDelete(event.id)"><i class="bx fs-6 bx-trash m-0"></i> удал.</button>
                                                                                    </div>

                                                                                </div>

                                                                            </template>
                                                                        </template>
                                                                        <template v-else-if="event.type_id === 2">
                                                                            <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                            <h6 class="card-title mb-1 text-white">Увольнение</h6>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="card-text m-0">
                                                                                    <small class="">комм: </small><span class="text-white">{{event.additional.comment}}</span>
                                                                                </p>
                                                                                <div class="d-flex">
                                                                                    <button class="btn fs-6 history_edit btn-light p-1 py-0" @click="editFiringEvent(event)"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                                    <button class="btn ms-2 fs-6 text-karmir btn-light p-1 py-0" @click="submitFiringDelete(event.id)"><i class="bx fs-6 bx-trash m-0"></i> удал.</button>
                                                                                </div>

                                                                            </div>

                                                                        </template>
                                                                        <template v-else-if="event.type_id === 3">
                                                                            <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                            <h6 class="card-title mb-1 text-white">Смена Отдела</h6>
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <p class="card-text m-0">
                                                                                    <small class="">с </small><span class="text_date">{{event.additional.from}}</span>
                                                                                    <small class="">в </small><span class="text_kanach">{{event.additional.to}}</span>
                                                                                </p>
                                                                                <div class="d-flex">
                                                                                    <button class="btn fs-6 history_edit btn-light p-1 py-0" @click="editCategoryChangeEvent(event)"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                                    <button class="btn ms-2 fs-6 text-karmir btn-light p-1 py-0" @click="submitCategoryChangeDelete(event.id)"><i class="bx fs-6 bx-trash m-0"></i> удал.</button>
                                                                                </div>

                                                                            </div>

                                                                        </template>
                                                                    </template>
                                                                    <template v-else-if="event.history_type === 'increase'">
                                                                        <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                        <h6 v-if="event.initial" class="card-title mb-1 text-white">Начальная Зарплата</h6>
                                                                        <h6 v-else class="card-title mb-1 text-white">Прибавка зарплаты</h6>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <p class="card-text m-0">
                                                                                <small class="">от </small><span class="text-white">{{makeMoney(event.old_value,true)}}</span>
                                                                                <small class="">до </small><span class="text-white">{{makeMoney(event.new_value,true)}}</span>
                                                                            </p>
                                                                            <div class="d-flex">
                                                                                <button class="btn fs-6 history_edit btn-light p-1 py-0" @click="editIncreaseEvent(event)"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                                <button v-if="!event.initial" class="btn ms-2 fs-6 text-karmir btn-light p-1 py-0" @click="submitIncreaseDelete(event.id)"><i class="bx fs-6 bx-trash m-0"></i> удал.</button>
                                                                            </div>

                                                                        </div>

                                                                    </template>
                                                                    <template v-else-if="event.history_type === 'holiday'">
                                                                        <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                        <h6 class="card-title mb-1 text-white">
                                                                            Отпуск
                                                                            <small v-if="event.paid" class="paid_holiday">опл. </small>
                                                                            <small v-else class="unpaid_holiday">неопл.</small>
                                                                        </h6>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <p class="card-text m-0">
                                                                                <small class="">с </small><span class="text-white">{{convertDateToShow(event.start_date)}}</span>
                                                                                <template v-if="event.end_date">
                                                                                    <small class="">до </small><span class="text-white">{{convertDateToShow(event.end_date)}}</span>
                                                                                    <small class="">итого </small><span class="text-white">{{smartCalculateDateDiff(event.start_date,event.end_date)}}</span>
                                                                                </template>
                                                                            </p>
                                                                            <button class="btn fs-6 history_edit bg_date btn-light p-1 py-0" @click="toSalariesPage(null,event.date,'holiday')"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                        </div>
                                                                    </template>
                                                                    <template v-else-if="event.history_type === 'day_off'">
                                                                        <div class="float-end text-white">{{convertDateToShow(event.date)}}</div>
                                                                        <h6 class="card-title mb-1 text-white">
                                                                            Выходной
                                                                            <small v-if="event.paid" class="paid_holiday">опл. </small>
                                                                            <small v-else class="unpaid_holiday">неопл.</small>
                                                                        </h6>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <p class="card-text m-0">
                                                                                <span v-if="event.additional.days" class="text-white">{{event.additional.days}}</span><small> дн.</small>

                                                                            </p>
                                                                            <button class="btn fs-6 history_edit btn-light p-1 py-0 bg_date" @click="toSalariesPage(null,event.date,'day_off')"><i class="bx fs-6 bx-edit m-0"></i> ред</button>
                                                                        </div>
                                                                    </template>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </template>
                                            <template v-else>

                                                <p class="p-2 text-center text-white" v-if="!hasSelectedHistoryType">Выберите Категорию</p>
                                                <p class="p-2 pt-5 text-center text-white" v-else>Список Пуст</p>
                                            </template>

                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="salaries" role="tabpanel">
                                    <div class="">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="example" class="table table-striped" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Зарплата</th>
                                                        <th>Премия</th>
                                                        <th>Дата</th>
                                                        <th>Ред.</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="salary in salaries">
                                                        <td>{{makeMoney(salary.amount,true)}}</td>
                                                        <td>{{makeMoney(salary.bonus,true)}}</td>
                                                        <td>{{salary.show_date}}</td>
                                                        <td><button @click="toSalariesPage(null,salary.for,'salary',salary.id)" class="bg_date btn btn-light p-0 px-1">ред <i class="m-0 bx bx-edit fs-6"></i></button></td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <hr>


            </div>

            <div class="row">
                <div class="col-4">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4> <span class="text_date">{{current_worker.category.base.title}}</span>/<span class="text_date">{{current_worker.category.title}}</span></h4>
                                    <h5 class="mb-0">Другие сотрудники данного отдела</h5>
                                </div>

                            </div>
                        </div>
                        <div class="customers-list p-3 mb-3 ">
                            <div  v-for="colleague in colleagues" class="customers-list-item d-flex align-items-center border-top border-bottom p-2">
                                <a :href="'/workers/homepage?id=' + colleague.id" class="ms-2">
                                    <h6 class="mb-1 font-14">{{colleague.name + '/ (' + makeMoney(colleague.last_increase.new_value,true) + ')'}}</h6>
                                    <p class="mb-0 font-13">{{colleague.additional}}</p>
                                </a>
                                <div class="list-inline d-flex customers-contacts ms-auto">
                                    <a title="Зарплата" role="button" class="list-inline-item on_hover_scale" @click="toSalariesPage(colleague.id)" ><i class="bx bx-ruble"></i></a>
                                    <a title="Профиль" :href="'/workers/homepage?id=' + colleague.id"  class="list-inline-item on_hover_scale"><i class="bx bx-user"></i></a>
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
    name: "WorkerPageComponent",
    data:function () {
        return {
            items_loaded:false,
            language: vdp_translation_ru.js,
            current_worker:{
                events:[],
                salaries:[],
                increases:[],
                salary:null,
                name:null,
                last_increase:{},
                last_event:{},
                category:{
                    base:{}
                }
            },
            workerNameWidth:165,
            edit_firing:{
                id:null,
                date:null,
                comment:null,
                deleted:false,
            },
            edit_increase:{
                id:null,
                date:null,
                salary:null,
                initial:false,
                deleted:false,
            },
            edit_category_change:{
                id:null,
                date:null,
                category:null,
                force:null,
                deleted:false,
            },
            edit_re_employ:{
                id:null,
                date:new Date(),
                comment:null,
                deleted:false,
                type:null,
            },
            firing:{
                date:new Date(),
                comment:null
            },
            increase:{
                date:new Date(),
                salary:null,
            },
            category_change:{
                date:new Date(),
                category:null,
                force:null,
            },
            re_employ:{
                date:new Date(),
                comment:null
            },
            datatable:null,
            colleagues:[],
            history:[],
            categories:[],
            salaries:[],
            posts:[],
            history_config:{
                show_all:true,
                sort_desc:true,
                types: {
                    event: {
                        type: 'event',
                        class: 'bx bx-briefcase',
                        selected:false,
                        title:'Карьера'
                    },
                    increase: {
                        type: 'increase',
                        class: 'bx bx-ruble text-blue',
                        selected:false,
                        title:'Прибавки'
                    },
                    holiday: {
                        type: 'holiday',
                        class: 'bx bx-world text-holiday',
                        selected:false,
                        title:'Отпуски'
                    },
                    day_off: {
                        type: 'day_off',
                        class: 'bx bx-calendar-event text-day-off',
                        selected:false,
                        title:'Выходные'
                    }
                }
            },
            salary:null,
            official_salary:null,
            current_worker_all:{
                events:[],
                salaries:[],
                increases:[],
                salary:null,
                last_increase:{},
                last_event:{},
                category:{
                    base:{}
                }
            },
            worker_id:null,
            form_disabled:false,
            MAIN_DATE:new Date()
        }

    },
    updated() {
    },
    watch:{
        'category_change.category':{
            handler:function (value){
                console.log('category_change.category', value)
            },
            deep:true
        },
        progressBar:function (value){

        },
        current_worker_all:{
            handler:function (value){

            },
            deep:true
        },
        'current_worker.name':function (value){
            this.$nextTick(function (){
                this.workerNameWidth = this.$refs.workers_name_span.clientWidth ?? 165
            })

        },
        current_worker:{
            handler:function (value){
                this.salary = this.makeMoney(value.last_increase.new_value)
                this.official_salary = this.makeMoney(value.official_salary)
                this.category_change.force = value.category_id
                this.increase.salary = null
                this.firing.comment = null
                this.re_employ.comment = null
                this.edit_category_change.deleted = false
                this.edit_increase.deleted = false
                this.edit_re_employ.deleted = false
                this.edit_firing.deleted = false
            },
            deep:true
        },
    },
    components: {
        Select2Component,
        Datepicker
    },
    created() {

    },
    mounted() {
        let worker_id = new URLSearchParams(window.location.search).get('id')
        axios.get('/axios/workers/get-worker-data/' + worker_id, {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(response => {

            this.current_worker = response.data.worker;
            this.current_worker_all = response.data.worker_all;
            this.history = response.data.history;
            this.colleagues = response.data.colleagues;
            this.salaries = response.data.salaries;
            this.worker_id = response.data.worker.id;
            this.category_change.category = response.data.worker.category_id;
            this.category_change.force = response.data.worker.category_id;
            this.categories = [];
            Object.values(response.data.categories).map(value => {
                this.categories.push({id:value.id, text:value.title + '/' + value.base.title})
            })
            this.posts = response.data.posts;
            this.items_loaded = true;
            this.$nextTick(function (){
                this.dataTable = $('#example').DataTable({
                    aaSorting: [],
                    columnDefs: [
                        { type: 'date-eu', targets: 2 }
                    ]
                });
            })
        });

    },
    computed:{
        selectedHistory(){
            let selected_history = [];
            if(this.history_config.show_all){
                selected_history = this.history;

            }else{
                this.history.map(value => {
                    if(this.history_config.types[value.history_type] && this.history_config.types[value.history_type].selected){
                        selected_history.push(value)
                    }
                })
            }
            return selected_history
        },
        getAvailableReEmployDate(){
            let date = new Date(this.current_worker_all.last_event.date).getDate()
            return new Date(new Date(this.current_worker_all.last_event.date).setDate(date + 1))
        },
        hasSelectedHistoryType(){
            return Object.values(this.history_config.types).find(value => value.selected)
        },
        progressBar(){
            let percent = 0;
            let background = '';
            let worker = this.current_worker;
            if(worker){
                let already_paid = this.alreadyPaidText(worker.id,false)
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
    },
    methods: {
        createMreate(){
            console.log('oooooooooooooooooooooooopsopdsospdospdopsodsd')
        },
        salaryChanged(ev){
            this.salary = this.makeMoney(ev.target.value)
        },
        increaseChanged(ev){
            this.increase.salary = this.makeMoney(ev.target.value)
        },
        editIncreaseChanged(ev){
            this.edit_increase.salary = this.makeMoney(ev.target.value)
        },
        officialSalaryChanged(ev){
            this.official_salary = this.makeMoney(ev.target.value)
        },
        profileExportData(){
            return {
                worker_id:this.worker_id,
                name:this.current_worker.name,
                post_id:this.current_worker.post_id,
                salary:this.salary ? this.salary.replace(/\D/g, ''): 0,
                last_increase_id:this.current_worker.last_increase.id,
                official_salary:this.official_salary ? this.official_salary.replace(/\D/g, ''): 0,
                additional:this.current_worker.additional,
            }
        },
        firingExportData(){
            return{
                worker_id:this.worker_id,
                date:this.firing.date,
                comment:this.firing.comment,
            }
        },
        editFiringExportData(){
            return{
                id:this.edit_firing.id,
                worker_id:this.worker_id,
                date:this.edit_firing.date,
                comment:this.edit_firing.comment,
                deleted:this.edit_firing.deleted,
            }
        },
        reEmployExportData(){
            return{
                worker_id:this.worker_id,
                date:this.re_employ.date,
                comment:this.re_employ.comment,
            }
        },
        editReEmployExportData(){
            return{
                id:this.edit_re_employ.id,
                worker_id:this.worker_id,
                date:this.edit_re_employ.date,
                comment:this.edit_re_employ.comment,
                deleted:this.edit_re_employ.deleted,
            }
        },
        increseExportData(){
            return{
                worker_id:this.worker_id,
                date:this.increase.date,
                salary:this.increase.salary ? this.increase.salary.replace(/\D/g, '') : 0,
            }
        },
        editIncreseExportData(){
            return{
                id:this.edit_increase.id,
                worker_id:this.worker_id,
                date:this.edit_increase.date,
                deleted:this.edit_increase.deleted,
                salary:this.edit_increase.salary ? this.edit_increase.salary.replace(/\D/g, '') : 0,
            }
        },
        categoryChangeExportData(){
            return{
                worker_id:this.worker_id,
                date:this.category_change.date,
                category:this.category_change.category,
            }
        },
        editCategoryChangeExportData(){
            return{
                id:this.edit_category_change.id,
                worker_id:this.worker_id,
                date:this.edit_category_change.date,
                category:this.edit_category_change.category,
                deleted:this.edit_category_change.deleted,
            }
        },
        submitProfileForm(){
            console.log(this.profileExportData())
            let data = this.profileExportData()
            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/update', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;

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
        },
        submitFiring(){
            let data = this.firingExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/firing', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.current_worker_all = response.data.worker_all;
                $('#fire_worker_modal').modal('hide');
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
        },
        submitEditFiring(){
            let data = this.editFiringExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/update-firing', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.current_worker_all = response.data.worker_all;
                $('#edit_fire_worker_modal').modal('hide');
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
        },
        submitReEmploy(){
            let data = this.reEmployExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/re-employ', data, config).then(response => {
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.current_worker_all = response.data.worker_all;
                $('#re_employ_modal').modal('hide');
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
        },
        submitEditReEmploy(){
            let data = this.editReEmployExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/update-re-employ', data, config).then(response => {
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.current_worker_all = response.data.worker_all;
                $('#edit_re_employ_modal').modal('hide');
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
        },
        submitIncrease(){
            let data = this.increseExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/salary/salary-increase', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                $('#increase_modal').modal('hide');
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
        },
        submitEditIncrease(){
            let data = this.editIncreseExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/salary/update-salary-increase', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                $('#edit_increase_modal').modal('hide');
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
        },
        submitCategoryChange(){
            let data = this.categoryChangeExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/category-change', data, config).then(response => {
                console.log(response.data)
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.colleagues = response.data.colleagues;
                $('#category_change_modal').modal('hide');
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
        },
        submitEditCategoryChange(){
            let data = this.editCategoryChangeExportData()

            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }
            axios.post('/axios/workers/update-category-change', data, config).then(response => {
                this.current_worker = response.data.worker;
                this.history = response.data.history;
                this.colleagues = response.data.colleagues;
                $('#edit_category_change_modal').modal('hide');
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
        },
        submitFiringDelete(id){

            if(confirm('Вы уверены что хотите удалить данное событие')){
                this.edit_firing.id = id
                this.edit_firing.deleted = true;
                this.submitEditFiring();
            }


        },
        submitReEmployDelete(id){
            if(confirm('Вы уверены что хотите удалить данное событие')){
                this.edit_re_employ.id = id
                this.edit_re_employ.deleted = true;
                this.submitEditReEmploy();
            }

        },
        submitIncreaseDelete(id){
            if(confirm('Вы уверены что хотите удалить данное событие')){
                this.edit_increase.id = id
                this.edit_increase.deleted = true;
                this.submitEditIncrease();
            }

        },
        submitCategoryChangeDelete(id){
            if(confirm('Вы уверены что хотите удалить данное событие')){
                this.edit_category_change.id = id
                this.edit_category_change.deleted = true;
                this.submitEditCategoryChange();
            }

        },
        editFiringEvent(event){
            this.edit_firing.id = event.id
            this.edit_firing.comment = event.additional.comment
            this.edit_firing.date = new Date(event.date)
            $('#edit_fire_worker_modal').modal('show')
        },
        editIncreaseEvent(event){
            this.edit_increase.id = event.id
            this.edit_increase.salary = this.makeMoney(event.new_value)
            this.edit_increase.date = new Date(event.date)
            this.edit_increase.initial = event.initial
            $('#edit_increase_modal').modal('show')
        },
        editCategoryChangeEvent(event){
            this.edit_category_change.id = event.id
            this.edit_category_change.category = event.additional.new_id
            this.edit_category_change.force = event.additional.new_id
            this.edit_category_change.date = new Date(event.date)
            $('#edit_category_change_modal').modal('show')
        },
        editReEmployEvent(event){
            this.edit_re_employ.id = event.id
            this.edit_re_employ.comment = event.additional.comment
            this.edit_re_employ.date = new Date(event.date)
            this.edit_re_employ.type = event.additional.type
            $('#edit_re_employ_modal').modal('show')
        },
        resetHistorySelection(){
            let config = this.history_config;
            config.show_all = true;
            Object.values(config.types).map(value => value.selected = false)
        },
        sortHistoryAscDesc(){
            this.history_config.sort_desc = !this.history_config.sort_desc
            this.history = this.history.reverse()
            console.log(this.history_config.sort_desc)
        },
        historyTypeSelected(type){
            let config = this.history_config;
            if(type === 'all'){
                config.show_all = !config.show_all;
            }else{
                if(config.show_all){
                    config.show_all = false;
                    Object.values(config.types).map(value => value.selected = false)
                    config.types[type].selected = true;
                }else{
                    config.types[type].selected = !config.types[type].selected
                }
            }
            console.log(this.history_config)
        },
        smartCalculateDateDiff(from,to){
            let m1 = moment(from);
            let m2 = moment(to);
            let diff = moment.preciseDiff(m1, m2);

            return diff;
        },
        convertDateToShow(value){
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
            let date = new Date(value);
            let day = date.getDate();
            day = day < 10 ? '0' + day : day;
            return day + ' ' + months[date.getMonth()] + ' ' + date.getFullYear()
        },
        setActiveTab(value){

        },
        toSalariesPage(id = null,date = null,event = null, salary_id = null){
            id = id ?? this.worker_id
            localStorage.setItem('worker_id',id);
            if(date){
                localStorage.setItem('date',date);
            }
            if(event){
                localStorage.setItem('event',event);
            }
            if(salary_id){
                localStorage.setItem('salary_to_focus',salary_id);
            }
            window.location.href = '/salary'
        },
        updateAllData(){
            this.form_disabled = true;
            axios.get('/axios/salary/get-select-bases/' + this.getCalculateYear, {
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).then(response => {
                console.log(response.data)
                this.$forceUpdate()
            });
        },
        salaryRemainderText(type_number = false){
            let remainder = 0;
            if(this.worker_id){
                let worker_obj =  this.current_worker;
                remainder = this.factualSalaryText(true) - this.alreadyPaidText(true)
            }
            if(type_number){
                return remainder
            }
            return this.makeMoney(remainder, true)
        },
        alreadyPaidText(type_number = false,all_needed = false){
            let paid = 0;
            let worker_obj = all_needed ? this.current_worker_all : this.current_worker;
            if(this.worker_id){

                worker_obj.salaries.map(value => {
                    if(!value.deleted_at){
                        paid += value.amount
                    }
                })
            }
            if(type_number){
                return paid
            }
            return this.makeMoney(paid,true)
        },
        startOfWork(return_date = false){
            let start = 0;
            let start_date = null;
            if(this.worker_id){
                let worker_obj = this.current_worker;
                if(worker_obj){
                    let event = worker_obj.events.find(value => value.type_id === 1)
                    if(event){
                        start = new Date(event.date).getDate() - 1
                    }
                }
            }
            if(return_date){
                return start_date
            }
            return start
        },
        dayAndMonth(initial_date, sign = null){
            sign = sign ?? '/'
            let date = new Date(initial_date);
            let month = date.getMonth()
            let day = date.getDate()
            return day + sign + this.fullDigitMonth(month+1)
        },
        fullDigitMonth(month){
            month += '';
            if(month.length === 1){
                month = '0' + month
            }
            return month
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
        extraHolidayText(all_needed = false,all_the_time = false){
            if(this.worker_id ){
                let worker_obj = all_the_time ? this.current_worker_all : this.current_worker;
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
        daysInMonth (month, year) {
            return new Date(year, month, 0).getDate();

        },
        dayOffText(all_needed = false,all_the_time = false){
            if(this.worker_id){
                let worker_obj = all_the_time ? this.current_worker_all : this.current_worker;
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
        salaryText(type_number = false){
            let salary = 0;
            if(this.worker_id){
                let worker =  this.current_worker;

                if(worker){
                    salary = worker.last_increase.new_value
                }
            }
            if(type_number){
                return salary
            }

            return this.makeMoney(salary,true);
        },
        factualSalaryText:function (type_number = false){
            let factual_salary = 0
            if(this.worker_id){

                let salary = this.salaryText(true)
                let daysBeforeStart = this.startOfWork();
                let extraHolidays = this.extraHolidayText();
                let unpaidDayOffs = this.dayOffText();
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

    },
}
</script>

<style scoped>

</style>
