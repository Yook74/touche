export class ContestInfo {
    host: string;
    name: string;
    startDate: string;
    startTime: string;
    freezeDelay: number;
    contestEndDelay: number;
    baseDirectory: string;
    // queueDirectory: string;
    // judgeDirectory: string;
    // dataDirectory: string;
    ignoreStandardError: boolean;
    hasStarted: boolean;
    judgeUsername: string;
    judgePassword: string;
    showTeams: boolean;
}
