import { Component, Input, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';

@Component({
    selector: 'confirm',
    templateUrl: './confirm.component.html'
})
export class ConfirmComponent {

    title: string = data.title;
    message: string = data.message;

    response: boolean = false;

    constructor (
      public dialogRef: MatDialogRef<ConfirmComponent>,
      @Inject(MAT_DIALOG_DATA) public data: any) {

        }

    onNoClick(): void {
      this.dialogRef.close(this.response);
    }
}
