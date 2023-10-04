// const onLoad = () => {
    //console.log("coucou");
    //addCollectionManagement('images', 'btn_add');
// };

// window.addEventListener('load', onLoad );

const addFormToCollection = (e) => {

    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

    addFormDeleteLink(item);
};

const addFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');

    const btn_label = item.parentElement.getAttribute('data-btn-delete-label');
    //const btn_label = item.parentElement.dataset.btnDeleteLabel;

    removeFormButton.innerText = btn_label ? 'Delete this ' + btn_label : 'Delete';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the status form
        item.remove();
    });
}
const addCollectionManagement = (ulClass, btnId) => {
    console.log("addCollectionManagement");
    const btn = document.getElementById(btnId);
    console.log(btn);
    btn.addEventListener("click", addFormToCollection);
    document
        .querySelectorAll('ul.' + ulClass + ' li')
        .forEach((item) => {
            addFormDeleteLink(item)
        });
}

addCollectionManagement('images', 'btn_add');