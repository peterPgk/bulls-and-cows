import { isEmpty } from "lodash";

export default () => ({
    data: {
        user: {},
        number: '',
    },
    userEmail: '',
    attempts: [],
    currentGuess: '',
    state: 'active',    //active, complete

    get user() {
        return this.data.user
    },

    get number() {
        return this.data.number
    },

    get hasUser() {
        return ! isEmpty(this.user)
    },

    get hasMainStat() {
        return ! isEmpty(this.data.mainStat)
    },

    get hasUserStat() {
        return ! isEmpty(this.data.userStat)
    },

    get storageKey() {
        return `${this.storageName}${this.user.id}`;
    },

    init() {
        this.fetchUrl = process.env.MIX_URL || 'http://nuvei.test';
        this.storageName = process.env.MIX_STORAGE_NAME || 'nuveiGame';
        axios.defaults.baseURL = process.env.MIX_URL || 'http://nuvei.test';

        //Check if user is already logged in and have game started
        if (! isEmpty(startedGameData)) {
            this.data = {...this.data, ...startedGameData}
        }
        //if user already have guesses
        this.checkStorage();
    },

    async startGame() {
        let email = this.hasUser ? this.user.email : this.userEmail;

        try {
            const response = await (await (axios.post('game/start', {
                email,
            }))).data.data;

            this.userEmail = '';
            this.state = 'active';
            this.attempts = [];
            this.currentGuess = '';

            this.data = {...this.data, ...response}

            // Eventually we can clear attempts from storage only if game is in `complete` state,
            // this way we will keep attempts, user made if he is un-logged automatically form session
            this.clearStorage();

        } catch (error) { alert(error) } //TODO: error handling
    },

    async logout() {
        await (axios.post('logout', {
            attempts: this.attempts,
        }));

        this.data = {user: {}, number: ''}
        this.clearStorage();
    },

    async storeResult() {
        try {
            const response = await ( await (axios.post('game/finish', {
                attempts: this.attempts,
            }))).data.data;

            this.data = {...this.data, ...response}
        } catch (error) { alert(error) }
    },

    validateInput(key) {
        if (key === 'Enter') {
            this.guess();
            return;
        }

        if (/^[0-9]$/.test(key) && this.currentGuess.length < this.number.length) {
            this.currentGuess += key;
        }
    },

    inputIsValid() {
        if (this.state === 'complete') {
            return;
        }
        return this.currentGuess.replace(/\D/g, '').length === this.number.length
    },

    parseGuess() {
        let theNum = [...this.number];
        return [...this.currentGuess].map((char, idx) => ({
            char,
            status: this.checkStatus(char, idx, theNum)
        }));
    },

    checkStatus(char, idx, theNum) {
        if (theNum[idx] === char) {
            theNum[idx] = null;
            return 'correct';
        }

        if (theNum.includes(char)) {
            theNum[theNum.indexOf(char)] = null;
            return "present";
        }

        return 'none';
    },

    guess() {
        if (this.inputIsValid()) {
            //I want first to color guess, then to check, to be visible that it is correct
            this.attempts.push(this.parseGuess());
            localStorage.setItem(`${this.storageKey}Attempts`, JSON.stringify(this.attempts))

            if (this.currentGuess === this.number) {
                this.state = 'complete';
                localStorage.setItem(`${this.storageKey}State`, this.state)
                this.storeResult()
                    .then(() => {
                        //
                    }
                );
            }

            this.currentGuess = '';
        }
    },

    checkStorage() {
        if (localStorage.hasOwnProperty(`${this.storageKey}Attempts`)) {
            this.attempts = [...JSON.parse(localStorage.getItem(`${this.storageKey}Attempts`))]
        }
        if (localStorage.hasOwnProperty(`${this.storageKey}State`)) {
            this.state = localStorage.getItem(`${this.storageKey}State`);
        }
    },

    clearStorage() {
        console.log("clearStorage");
        localStorage.removeItem(`${this.storageKey}Attempts`);
        localStorage.removeItem(`${this.storageKey}State`);
    }
})
