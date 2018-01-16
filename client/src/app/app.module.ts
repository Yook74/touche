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
import { LiveProblemsComponent } from './contest/contest_live/problems/problems.component';
import { ProblemAttachmentsComponent } from './contest/contest_live/problems/attachments/attachments.component';
import { LiveStandingsComponent } from './contest/contest_live/standings/standings.component';
import { LiveTimerComponent } from './contest/contest_live/timer/timer.component';

import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { JudgeSideNavComponent } from './contest/contest_judge/sidenav/sidenav.component';
import { JudgeContestDetailComponent } from './contest/contest_judge/contest_detail/contest_detail.component';
import { JudgeSubmissionsComponent } from './contest/contest_judge/submissions/submissions.component';
import { JudgeClarificationsComponent } from './contest/contest_judge/clarifications/clarifications.component';
import { JudgeProblemsComponent } from './contest/contest_judge/problems/problems.component';
import { JudgeStandingsComponent } from './contest/contest_judge/standings/standings.component';
import { JudgeTimerComponent } from './contest/contest_judge/timer/timer.component';

import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';
import { AdminSideNavComponent } from './contest/contest_admin/sidenav/sidenav.component';
import { AdminContestDetailComponent } from './contest/contest_admin/contest_detail/contest_detail.component';
import { AdminProblemsComponent } from './contest/contest_admin/problems/problems.component';
import { AdminTeamsComponent } from './contest/contest_admin/teams/teams.component';
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
    LiveProblemsComponent,
    ProblemAttachmentsComponent,
    LiveStandingsComponent,
    LiveTimerComponent,
    ContestJudgeComponent,
    JudgeSideNavComponent,
    JudgeContestDetailComponent,
    JudgeSubmissionsComponent,
    JudgeClarificationsComponent,
    JudgeProblemsComponent,
    JudgeStandingsComponent,
    JudgeTimerComponent,
    ContestAdminComponent,
    AdminSideNavComponent,
    AdminContestDetailComponent,
    AdminProblemsComponent,
    AdminTeamsComponent,
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
    ProblemAttachmentsComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
