import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';
import { MatButtonModule, MatSidenavModule, MatToolbarModule, MatIconModule, MatTableModule } from '@angular/material';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { FlexLayoutModule } from '@angular/flex-layout';

import { AppComponent } from './app.component';
import { TableComponent } from './components/data_table/table.component';
import { FieldDirective } from './components/data_table/field.directive';
import { TableFieldComponent } from './components/data_table/table_field.component';
import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestComponent } from './contest/contest.component';

import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { LiveSideNavComponent } from './contest/contest_live/sidenav/sidenav.component';
import { LiveContestDetailComponent } from './contest/contest_live/contest_detail/contest_detail.component';
import { LiveClarificationsComponent } from './contest/contest_live/clarifications/clarifications.component';
import { LiveClarificationDetailsComponent } from './contest/contest_live/clarifications/details/details.component';
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
import { JudgeSubmissionsComponent } from './contest/contest_judge/submissions/submissions.component';
import { JudgeSubmissionJudgeComponent } from './contest/contest_judge/submissions/judge/judge.component';
import { JudgeClarificationsComponent } from './contest/contest_judge/clarifications/clarifications.component';
import { JudgeClarificationAnswerComponent } from './contest/contest_judge/clarifications/answer/answer.component';
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
import { AdminTeamsComponent } from './contest/contest_admin/teams/teams.component';
import { AdminTeamCategoriesComponent } from './contest/contest_admin/teams/categories/categories.component';
import { AdminTeamDeleteComponent } from './contest/contest_admin/teams/delete/delete.component';
import { AdminTeamEditComponent } from './contest/contest_admin/teams/edit/edit.component';
import { AdminLanguagesComponent } from './contest/contest_admin/languages/languages.component';
import { AdminAdvancedComponent } from './contest/contest_admin/advanced/advanced.component';
import { AdminTimerComponent } from './contest/contest_admin/timer/timer.component';

import { ContestNameService } from './services/contest_name.service';
import { BaseService } from './services/model_services/base.service';
import { CategoryService } from './services/model_services/category.service';
import { ClarificationService } from './services/model_services/clarification.service';
import { ContestInfoService } from './services/model_services/contest_info.service';
import { ProblemService } from './services/model_services/problem.service';
import { ResponseService } from './services/model_services/response.service';
import { StandingService } from './services/model_services/standing.service';
import { SubmissionService } from './services/model_services/submission.service';
import { TeamService } from './services/model_services/team.service';


@NgModule({
  declarations: [
    AppComponent,
    FieldDirective,
    TableFieldComponent,
    TableComponent,
    CreateContestComponent,
    ContestComponent,
    ContestLiveComponent,
    LiveSideNavComponent,
    LiveContestDetailComponent,
    LiveClarificationsComponent,
    LiveClarificationDetailsComponent,
    LiveProblemsComponent,
    LiveProblemAttachmentsComponent,
    LiveProblemSubmitComponent,
    LiveStandingsComponent,
    LiveStandingProblemsComponent,
    LiveStandingFinalScoreComponent,
    LiveTimerComponent,
    ContestJudgeComponent,
    JudgeSideNavComponent,
    JudgeContestDetailComponent,
    JudgeSubmissionsComponent,
    JudgeSubmissionJudgeComponent,
    JudgeClarificationsComponent,
    JudgeClarificationAnswerComponent,
    JudgeProblemsComponent,
    JudgeProblemAttachmentsComponent,
    JudgeStandingsComponent,
    JudgeStandingFinalScoreComponent,
    JudgeStandingProblemsComponent,
    JudgeTimerComponent,
    ContestAdminComponent,
    AdminSideNavComponent,
    AdminContestDetailComponent,
    AdminProblemsComponent,
    AdminProblemDataSetsComponent,
    AdminProblemAttachmentsComponent,
    AdminProblemDeleteComponent,
    AdminProblemEditComponent,
    AdminTeamsComponent,
    AdminTeamEditComponent,
    AdminTeamDeleteComponent,
    AdminTeamCategoriesComponent,
    AdminLanguagesComponent,
    AdminAdvancedComponent,
    AdminTimerComponent
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
    MatTableModule
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
    TeamService
  ],
  entryComponents: [
    LiveProblemAttachmentsComponent,
    LiveProblemSubmitComponent,
    LiveClarificationDetailsComponent,
    LiveStandingProblemsComponent,
    LiveStandingFinalScoreComponent,
    JudgeClarificationAnswerComponent,
    JudgeProblemAttachmentsComponent,
    JudgeStandingProblemsComponent,
    JudgeStandingFinalScoreComponent,
    JudgeSubmissionJudgeComponent,
    AdminProblemDataSetsComponent,
    AdminProblemDeleteComponent,
    AdminProblemAttachmentsComponent,
    AdminProblemEditComponent,
    AdminTeamEditComponent,
    AdminTeamDeleteComponent,
    AdminTeamCategoriesComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
