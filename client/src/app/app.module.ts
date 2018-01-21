import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';
import { MatButtonModule, MatSidenavModule, MatToolbarModule, MatIconModule, MatTableModule, MatCheckboxModule, MatFormFieldModule, MatInputModule, MatDialogModule, MatSelectModule } from '@angular/material';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { FlexLayoutModule } from '@angular/flex-layout';
import { CookieService } from 'ngx-cookie-service';

import { AppComponent } from './app.component';
import { TableComponent } from './components/data_table/table.component';
import { FieldDirective } from './components/data_table/field.directive';
import { TableFieldComponent } from './components/data_table/table_field.component';
import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestComponent } from './contest/contest.component';
import { ConfirmComponent } from './components/confirm/confirm.component';

import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { ContestLiveLogin } from './contest/contest_live/login/login.component';
import { LiveSideNavComponent } from './contest/contest_live/sidenav/sidenav.component';
import { LiveContestDetailComponent } from './contest/contest_live/contest_detail/contest_detail.component';
import { LiveClarificationsComponent } from './contest/contest_live/clarifications/clarifications.component';
import { LiveClarificationDetailsComponent } from './contest/contest_live/clarifications/details/details.component';
import { LiveClarificationRequestComponent } from './contest/contest_live/clarifications/request/request.component';
import { LiveProblemsComponent } from './contest/contest_live/problems/problems.component';
import { LiveProblemAttachmentsComponent } from './contest/contest_live/problems/attachments/attachments.component';
import { LiveProblemSubmitComponent } from './contest/contest_live/problems/submit/submit.component';
import { LiveStandingsComponent } from './contest/contest_live/standings/standings.component';
import { LiveStandingFinalScoreComponent } from './contest/contest_live/standings/final_score/final_score.component';
import { LiveStandingProblemsComponent } from './contest/contest_live/standings/problems/problems.component';
import { LiveTimerComponent } from './contest/contest_live/timer/timer.component';

import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { JudgeSideNavComponent } from './contest/contest_judge/sidenav/sidenav.component';
import { JudgeContestDetailComponent } from './contest/contest_judge/contest_detail/contest_detail.component';
import { JudgeContestDetailStatusComponent } from './contest/contest_judge/contest_detail/status/status.component';
import { JudgeContestDetailTeamsComponent } from './contest/contest_judge/contest_detail/teams/teams.component';
import { JudgeContestDetailTimeRemainingComponent } from './contest/contest_judge/contest_detail/time_remaining/time_remaining.component';
import { JudgeSubmissionsComponent } from './contest/contest_judge/submissions/submissions.component';
import { JudgeSubmissionJudgeComponent } from './contest/contest_judge/submissions/judge/judge.component';
import { JudgeClarificationsComponent } from './contest/contest_judge/clarifications/clarifications.component';
import { JudgeClarificationAnswerComponent } from './contest/contest_judge/clarifications/answer/answer.component';
import { JudgeClarificationMakeComponent } from './contest/contest_judge/clarifications/make/make.component';
import { JudgeProblemsComponent } from './contest/contest_judge/problems/problems.component';
import { JudgeProblemAttachmentsComponent } from './contest/contest_judge/problems/attachments/attachments.component';
import { JudgeStandingsComponent } from './contest/contest_judge/standings/standings.component';
import { JudgeStandingFinalScoreComponent } from './contest/contest_judge/standings/final_score/final_score.component';
import { JudgeStandingProblemsComponent } from './contest/contest_judge/standings/problems/problems.component';
import { JudgeTimerComponent } from './contest/contest_judge/timer/timer.component';

import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';
import { AdminSideNavComponent } from './contest/contest_admin/sidenav/sidenav.component';
import { AdminContestDetailComponent } from './contest/contest_admin/contest_detail/contest_detail.component';
import { AdminProblemsComponent } from './contest/contest_admin/problems/problems.component';
import { AdminProblemAttachmentsComponent } from './contest/contest_admin/problems/attachments/attachments.component';
import { AdminProblemDataSetsComponent } from './contest/contest_admin/problems/data_sets/data_sets.component';
import { AdminProblemDeleteComponent } from './contest/contest_admin/problems/delete/delete.component';
import { AdminProblemEditComponent } from './contest/contest_admin/problems/edit/edit.component';
import { AdminProblemAddComponent } from './contest/contest_admin/problems/add/add.component';
import { AdminTeamsComponent } from './contest/contest_admin/teams/teams.component';
import { AdminTeamCategoryComponent } from './contest/contest_admin/teams/category/category.component';
import { AdminTeamDeleteComponent } from './contest/contest_admin/teams/delete/delete.component';
import { AdminTeamEditComponent } from './contest/contest_admin/teams/edit/edit.component';
import { AdminTeamAddComponent } from './contest/contest_admin/teams/add/add.component';
import { AdminLanguagesComponent } from './contest/contest_admin/languages/languages.component';
import { AdminAdvancedComponent } from './contest/contest_admin/advanced/advanced.component';
import { AdminTimerComponent } from './contest/contest_admin/timer/timer.component';
import { AdminSitesComponent } from './contest/contest_admin/teams/sites/sites.component';
import { AdminSiteAddComponent } from './contest/contest_admin/teams/sites/add/add.component';
import { AdminSiteEditComponent } from './contest/contest_admin/teams/sites/edit/edit.component';
import { AdminSiteDeleteComponent } from './contest/contest_admin/teams/sites/delete/delete.component';
import { AdminCategoriesComponent } from './contest/contest_admin/teams/categories/categories.component';
import { AdminCategoryAddComponent } from './contest/contest_admin/teams/categories/add/add.component';
import { AdminCategoryEditComponent } from './contest/contest_admin/teams/categories/edit/edit.component';
import { AdminCategoryDeleteComponent } from './contest/contest_admin/teams/categories/delete/delete.component';

import { ContestNameService } from './services/contest_name.service';
import { BaseService } from './services/model_services/base.service';
import { CategoryService } from './services/model_services/category.service';
import { ClarificationService } from './services/model_services/clarification.service';
import { ContestInfoService } from './services/model_services/contest_info.service';
import { ProblemService } from './services/model_services/problem.service';
import { ResponseService } from './services/model_services/response.service';
import { StandingService } from './services/model_services/standing.service';
import { SubmissionService } from './services/model_services/submission.service';
import { SiteService } from './services/model_services/site.service';
import { TeamService } from './services/model_services/team.service';
import { AuthenticationService } from './services/authentication.service';
import { TeamAuthenticatedService } from './services/team_authenticated.service';
import { AdminAuthenticatedService } from './services/admin_authenticated.service';
import { JudgeAuthenticatedService } from './services/judge_authenticated.service';
import { ContestJudgeLogin } from './contest/contest_judge/login/login.component';
import { ContestAdminLogin } from './contest/contest_admin/login/login.component';


@NgModule({
  declarations: [
    AppComponent,
    FieldDirective,
    TableFieldComponent,
    TableComponent,
    CreateContestComponent,
    ContestComponent,
    ConfirmComponent,
    ContestLiveComponent,
    ContestLiveLogin,
    LiveSideNavComponent,
    LiveContestDetailComponent,
    LiveClarificationsComponent,
    LiveClarificationDetailsComponent,
    LiveClarificationRequestComponent,
    LiveProblemsComponent,
    LiveProblemAttachmentsComponent,
    LiveProblemSubmitComponent,
    LiveStandingsComponent,
    LiveStandingProblemsComponent,
    LiveStandingFinalScoreComponent,
    LiveTimerComponent,
    ContestJudgeComponent,
    ContestJudgeLogin,
    JudgeSideNavComponent,
    JudgeContestDetailComponent,
    JudgeContestDetailTeamsComponent,
    JudgeContestDetailStatusComponent,
    JudgeContestDetailTimeRemainingComponent,
    JudgeSubmissionsComponent,
    JudgeSubmissionJudgeComponent,
    JudgeClarificationsComponent,
    JudgeClarificationAnswerComponent,
    JudgeClarificationMakeComponent,
    JudgeProblemsComponent,
    JudgeProblemAttachmentsComponent,
    JudgeStandingsComponent,
    JudgeStandingFinalScoreComponent,
    JudgeStandingProblemsComponent,
    JudgeTimerComponent,
    ContestAdminComponent,
    ContestAdminLogin,
    AdminSideNavComponent,
    AdminContestDetailComponent,
    AdminProblemsComponent,
    AdminProblemDataSetsComponent,
    AdminProblemAttachmentsComponent,
    AdminProblemDeleteComponent,
    AdminProblemEditComponent,
    AdminProblemAddComponent,
    AdminTeamsComponent,
    AdminTeamEditComponent,
    AdminTeamDeleteComponent,
    AdminTeamCategoryComponent,
    AdminTeamAddComponent,
    AdminLanguagesComponent,
    AdminAdvancedComponent,
    AdminTimerComponent,
    AdminSitesComponent,
    AdminSiteEditComponent,
    AdminSiteAddComponent,
    AdminSiteDeleteComponent,
    AdminCategoriesComponent,
    AdminCategoryEditComponent,
    AdminCategoryAddComponent,
    AdminCategoryDeleteComponent
  ],
  imports: [
    BrowserModule,
    NoopAnimationsModule,
    MatButtonModule,
    MatSidenavModule,
    RoutingModule,
    HttpClientModule,
    FormsModule,
    MatToolbarModule,
    MatIconModule,
    FlexLayoutModule,
    MatTableModule,
    MatCheckboxModule,
    MatFormFieldModule,
    MatInputModule,
    MatDialogModule,
    MatSelectModule
  ],
  providers: [
    ContestNameService,
    BaseService,
    CategoryService,
    ClarificationService,
    ContestInfoService,
    ProblemService,
    ResponseService,
    StandingService,
    SubmissionService,
    SiteService,
    TeamService,
    AuthenticationService,
    CookieService,
    TeamAuthenticatedService,
    AdminAuthenticatedService,
    JudgeAuthenticatedService
  ],
  entryComponents: [
    ConfirmComponent,
    LiveProblemAttachmentsComponent,
    LiveProblemSubmitComponent,
    LiveClarificationDetailsComponent,
    LiveClarificationRequestComponent,
    LiveStandingProblemsComponent,
    LiveStandingFinalScoreComponent,
    JudgeContestDetailTeamsComponent,
    JudgeContestDetailStatusComponent,
    JudgeContestDetailTimeRemainingComponent,
    JudgeClarificationAnswerComponent,
    JudgeClarificationMakeComponent,
    JudgeProblemAttachmentsComponent,
    JudgeStandingProblemsComponent,
    JudgeStandingFinalScoreComponent,
    JudgeSubmissionJudgeComponent,
    AdminProblemDataSetsComponent,
    AdminProblemDeleteComponent,
    AdminProblemAttachmentsComponent,
    AdminProblemEditComponent,
    AdminProblemAddComponent,
    AdminTeamEditComponent,
    AdminTeamDeleteComponent,
    AdminTeamCategoryComponent,
    AdminTeamAddComponent,
    AdminSitesComponent,
    AdminSiteEditComponent,
    AdminSiteAddComponent,
    AdminSiteDeleteComponent,
    AdminCategoriesComponent,
    AdminCategoryEditComponent,
    AdminCategoryAddComponent,
    AdminCategoryDeleteComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
