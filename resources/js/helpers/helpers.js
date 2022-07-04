// Получаем данные пользователя из хранилища
export const userData = () => {
    let userData = null;
    try{
        userData = JSON.parse(localStorage.getItem('user'));
    }catch (e) {

    }

    if(!userData){
        userData = {
            name: '',
            avatar: '',
            avatar_src: '',
        };
    }

    return userData;
}

export const setUserData = (userData) => {
    try{
        localStorage.setItem('user', JSON.stringify(userData));
    }catch (e) {

    }
}
