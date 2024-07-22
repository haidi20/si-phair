let io;

module.exports = {
    setIo: function (newIo) {
        io = newIo;
    },
    getIo: function () {
        if (!io) {
            throw new Error('Must call setIo before getIo');
        }
        return io;
    }
};
