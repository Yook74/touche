import { Component } from '@angular/core';

@Component({
    templateUrl: './standings.component.html'
})
export class LiveStandingsComponent {
    headers: string[] = ['Rank', 'Team', 'Prob#1','Prob#2','Prob#3','Prob#4','Prob#5','Prob#6','Prob#7','Prob#8','Final Score'];
    constructor() { }
}
