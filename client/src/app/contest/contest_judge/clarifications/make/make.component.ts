import { Component, Input, Inject } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';

@Component({
    selector: 'judge-clarification-make',
    templateUrl: './make.component.html'
})
export class JudgeClarificationMakeComponent {

    someValue: boolean = false;

    problems = [
        {value: 'problem-0', viewValue: 'General'},
        {value: 'problem-1', viewValue: 'Problem 1'},
        {value: 'problem-2', viewValue: 'Problem 2'},
        {value: 'problem-3', viewValue: 'Problem 3'}
    ];


    constructor (
      public dialogRef: MatDialogRef<JudgeClarificationMakeComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) { }

    onNoClick(): void {
      this.dialogRef.close(this.someValue);
    }
}
