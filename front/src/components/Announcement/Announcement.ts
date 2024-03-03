export class Announcement {
    constructor(
        public title: String = '',
        public description: String = '',
        public cost: Number = 0,
    ) {
    }

    public reset() {
        this.title = '';
        this.description = '';
        this.cost = 0;
    }
}