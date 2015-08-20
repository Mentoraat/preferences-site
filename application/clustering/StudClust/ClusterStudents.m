

load testdata
nStudents = size(Dpref,1);
% fitnessfcn = @(x,Dpref,Dbelbin)ClustStudFit;
nClust = 5;
MaxMutation = 4;

%%%%%%%%%%%%%%%%%%

options = gaoptimset(@ga);
options = gaoptimset(options,...
    'PopulationSize',[200 200],...
    'CreationFcn',{@ClustStudCreate,nClust},...
    'CrossoverFcn',@ClustStudCrossover,...
    'MutationFcn',{@ClustStudMut,MaxMutation},...
    'PlotFcn',{@gaplotbestf,@gaplotscores},...
    'PlotInterval',3,...
    'TolFun',1e-10,...
    'Generations',1000); 
    
IntCon = 1:nStudents;
nvars = nStudents;

[x,fval,exitflag] = ga({@ClustStudFit,Dpref,Dbelbin},nvars,[],[],[],[],...
    [],[],[],[],options);

[~,Is] = sort(x);
close all
imagesc(Dpref(Is,Is))
figure();
imagesc(Dbelbin(Is,:))

