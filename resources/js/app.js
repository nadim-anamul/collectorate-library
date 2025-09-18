import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

window.notifications = function () {
    return {
        open: false,
        unread: 0,
        items: [],
        _poller: null,
        _audioCtx: null,
        isAdmin: window.userIsAdmin || false,
        init() {
            this.fetch();
            this._startPolling();
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.fetch();
            }
        },
        async fetch() {
            try {
                const { data } = await window.axios.get("/notifications");
                const previousUnread = this.unread;
                this.unread = data.unread;
                this.items = data.items;
                if (this.unread > previousUnread) {
                    this._beep();
                }
            } catch (e) {
                /* noop */
            }
        },
        async markAllRead() {
            try {
                await window.axios.post("/notifications/read");
                this.fetch();
            } catch (e) {}
        },
        async markOneRead(n) {
            try {
                await window.axios.post("/notifications/read", { id: n.id });
                this.fetch();
                if (n.data.url) {
                    window.location = n.data.url;
                }
            } catch (e) {}
        },
        _startPolling() {
            if (this._poller) return;
            this._poller = setInterval(() => {
                if (document.hidden) return; // skip when tab not visible
                this.fetch();
            }, 20000); // 20s
        },
        _beep() {
            try {
                if (!this._audioCtx) {
                    const Ctx =
                        window.AudioContext || window.webkitAudioContext;
                    this._audioCtx = Ctx ? new Ctx() : null;
                }
                if (!this._audioCtx) return;
                const durationMs = 150;
                const oscillator = this._audioCtx.createOscillator();
                const gainNode = this._audioCtx.createGain();
                oscillator.type = "sine";
                oscillator.frequency.setValueAtTime(
                    880,
                    this._audioCtx.currentTime
                );
                gainNode.gain.setValueAtTime(
                    0.0001,
                    this._audioCtx.currentTime
                );
                gainNode.gain.exponentialRampToValueAtTime(
                    0.05,
                    this._audioCtx.currentTime + 0.01
                );
                gainNode.gain.exponentialRampToValueAtTime(
                    0.0001,
                    this._audioCtx.currentTime + durationMs / 1000
                );
                oscillator.connect(gainNode);
                gainNode.connect(this._audioCtx.destination);
                oscillator.start();
                oscillator.stop(this._audioCtx.currentTime + durationMs / 1000);
            } catch (_) {}
        },
    };
};


Alpine.start();
