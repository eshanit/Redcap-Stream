const numberWithSpaces = (x: number) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

export default numberWithSpaces